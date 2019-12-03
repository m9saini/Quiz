<?php

namespace Modules\Partners\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Partners\Entities\Partners;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Requests\Backend\Access\User\MediaRequest;
use Modules\Partners\Http\Requests\CreatePartnersRequest;
use DataTables;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    protected $model= 'Partners';
    protected $fillable = ['name','slug','description','image','status'];
    public function __construct(Request $request,Partners $Partners){
        $this->middleware(['auth','ability']);
        $this->request = $request;
        $this->Partners = $Partners;
     
    }
    public function index(Request $request)
    {
       
            if($request->ajax()){
                $model = $this->model;
                $list=Partners::latest()->get();
                return DataTables::of($list)
                 ->addIndexColumn()
                 ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]], 
                            getStatusAI($list->status)=>[strtolower($model).'.status',[$list->slug]]
                    ]);
                   
                        $status =keyExist($dispalyButton, getStatusAI($list->status));
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $edit.$delete.$status;
                    })
               ->editColumn(strtolower('name'),function($list){
                 return (str_limit(strip_tags($list->name),20));
               })
               ->editColumn(strtolower('description'),function($list){
                  return(str_limit(strip_tags($list->description),200));
               })
               ->editColumn('image', function($list){
                            return '<a class="group1" href="'.$list->picture_path.'" title="'.str_limit(strip_tags($list->description), 300).'"><img src="'.$list->picture_path.'" class="img-thumbnail" alt="" width="120" id="" ></a>';
                })
               ->editColumn('status', function($list){
                            return ($list->status==1) ? 'Active':'Inactive';
                })
               ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
                    })
              ->rawColumns(['image','action'])
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
    public function store(CreatePartnersRequest $request)
    {
         $fillable = $request->only($this->fillable);
         $fillable['slug'] = $request->name;
         if ($request->get('image')) $fillable['image'] = $request->get('image');
         Partners::create($fillable);
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
        return view('partners::show');
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
    public function update(Request $request, $id)
    {
        $fillable = $request->only($this->fillable);
        if ($request->get('image')) $fillable['image'] = $request->get('image');
        $model = $this->model;
        $update = $this->$model->find($id);
        $update->fill($fillable);
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


    public function status(Request $request,$id)
    {   
       
       
           $model = $this->model;
           $data = $this->$model->findBySlug($id);
           $active = $data->status;
            if ($id != null) 
            {
                if($active==1)
                {
                     $data->status =0;
                      $data->save();
                }
                else
                { 
                    $data->status =1;
                      $data->save();
                }
              
              if($request->ajax()){
                    $type = 'success'; 
                    $message = $this->model.' status change successfully.';
               }else{
                      $type = 'warning'; 
                      $message = ' You have something went wrong.';
               }
                            
            }else{
                     $type = 'warning'; 
                     $message = ' You have something went wrong.';
            }
            return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
            return redirect()->route(strtolower($this->model).'.index');
        
    }
}
