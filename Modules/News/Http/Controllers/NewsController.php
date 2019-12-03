<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\News\Entities\News;
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\Backend\Access\User\MediaRequest;
use Modules\News\Http\Requests\CreateNewsRequest;
use Modules\News\Http\Requests\UpdateNewsRequest;
use DB,View,Session,Redirect,Mail,Config,Auth;
use DataTables; 

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

      protected $model = 'News';
      protected $filleable = ['slug','title','short_description','description','news_order','image'];
     
     public function __construct(Request $request,News $News)
    { 
        $this->middleware(['auth','ability'], ['except' => ['create','ajaxdata']]);
        $this->request = $request;
        $this->News = $News;
        
        
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            $model = $this->model;
            $lists = News::latest()->get();  
            return DataTables::of($lists)
                    ->addIndexColumn()
                    ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],]);
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $edit.$delete;
                    }) 
                    ->editColumn('title', function($list){
                        return str_limit(strip_tags($list->title), 300);
                    })
                    ->editColumn('short_description', function($list){
                        return str_limit(strip_tags($list->short_description), 300);
                    }) 
                    ->editColumn('description', function($list){
                        return str_limit(strip_tags($list->description), 300);
                    })
                    ->editColumn('image', function($list){
                            return '<a class="group1" href="'.$list->picture_path.'" title="'.str_limit(strip_tags($list->description), 300).'"><img src="'.$list->picture_path.'" class="img-thumbnail" alt="" width="120" id="" ></a>';
                    })
                    ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
                    })
                    ->rawColumns(['image', 'action'])
                    ->make(true);
       }
        return view(strtolower($this->model).'::index')->withModel(strtolower($this->model));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
       return view(strtolower($this->model).'::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateNewsRequest $request)
    {
         $filleable = $request->only($this->filleable);
        
         $filleable['slug'] = time();
        if ($request->get('image')) $filleable['image'] = $request->get('image');
         //dd($request->all());
         $data=News::create($filleable);
         Session::flash('success', $this->model.' created successfully.');
         return redirect()->route(strtolower($this->model).'.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('news::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
         $model = $this->model;
         $data = $this->$model->findBySlug($id);
        return view(strtolower($this->model).'::edit',compact('data'))->withModel(strtolower($this->model));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update($id,UpdateNewsRequest $request)
    {
        $filleable = $request->only($this->filleable);
        if ($request->get('image')) $filleable['image'] = $request->get('image');
        $model = $this->model;
        $update = $this->$model->find($id);
        $update->fill($filleable);
        $update->save();
        Session::flash('success', $model. ' updated successfully.');
        return redirect()->route(strtolower($this->model).'.index');
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $model = $this->model;
            $delete = $this->$model->findBySlug($slug);
            $delete->destroy($delete->id);
            $type = 'success'; $message = $this->model.' deleted successfully.';
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
            }
        }catch (QueryException $e){
            $type = 'warning'; $message = 'You cannot delete this record yet,try again later';
        }
        if($request->ajax()){
            return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
        }
        Session::flash($type, $message);
        return redirect()->route(strtolower($this->model).'.index');
    }

   


    public function saveMedia(MediaRequest $request)
    { 
        try{
           $filename = upload($request->file('files'),storage_path() . '/app/public/'.strtolower($this->model).'/');
            return response()->json(["status"=>true,"filename"=>$filename]); 
        }catch (\Exception $e) {
              return response()->json(["status"=>false,"message"=>$e->getMessage()]);
        }  
    }
}
