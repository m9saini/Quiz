<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\Backend\Access\User\MediaRequest;
use Modules\Category\Http\Requests\CreateCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Dyanamic Controller model and function
     * Change $model = '' according to your module
     * This work only when model name & routing name is same
     * Change Fillable params according to your tables
     **/
     protected $model = 'Category';
     protected $filleable = ['parent_id','title','description','category_order'];
     public function __construct(Request $request, Category $Category,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['create','ajaxdata']]);
        $this->request = $request;
        $this->Category = $Category;
        $this->alert = $alertController;
    }

     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view(strtolower($this->model).'::index')->withModel(strtolower($this->model));
    }

     /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData()
    {
        try {
            DB::statement(DB::raw('set @rownum=0'));
            $model = $this->model;
            $lists = $this->$model->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                                ->get();  
            return DataTables::of($lists)
                    ->addColumn('action', function($list) use($model){
                        $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],getStatusAI($list->status)=>[strtolower($model).'.status',[$list->slug]],]);
                        $status =keyExist($dispalyButton, getStatusAI($list->status));
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $status.$edit.$delete;
                    })  
                    ->editColumn('description', function($list){
                        return str_limit(strip_tags($list->description), 300);
                    })
                    ->editColumn('category_image', function($list){ 
                            return '<a class="group1" href="'.$list->picture_path.'" title="'.str_limit(strip_tags($list->description), 300).'"><img src="'.$list->picture_path.'" class="img-thumbnail" alt="" width="120" id="" ></a>';
                    })
                    ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
                    })
                    ->rawColumns(['category_image', 'action'])
                    ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $catList=$this->getList();

        return view(strtolower($this->model).'::create', compact('catList'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $filleable = $request->only($this->filleable);
       // $filleable['slug'] = time();
        if ($request->get('category_image')) $filleable['category_image'] = $request->get('category_image');
        //$model = $this->model;
        //$filleable['title']=$request->get('name.0');
        //$this->$model->fill($filleable);
        $name=($request->get('name'));
        $model= new Category();
        $model->title=$name[0];
        $model->lang_id=DEFAULT_LANGUAGE_CODE;
        $model->category_order=$request->get('category_order');
        $model->status=($request->get('status'))?$request->get('status'):1;
        if($model->save()){
            foreach ($lang=getLanguageList() as $key => $value) { //dd($value);
                $catDesc['category_id']=$model->id;
                $catDesc['name']=$name[$key];
                $catDesc['lang_id']=$value['code'];
                $model->afterSave($catDesc);
            }
            
        }
        Session::flash('success', $this->model.' created successfully.');
        return redirect()->route(strtolower($this->model).'.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($slug)
    {
        $model = $this->model;
        if($this->getRecord($slug) == false) return $this->ValidateHeader();
        $id = $this->getRecord($slug);
        $data = $this->$model->findorFail($id);
        return view(strtolower($this->model).'::show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->model;
        $data = $this->$model->findBySlug($id);
        $catList=$this->getList($id);
        return view(strtolower($this->model).'::edit',compact('data','catList'))->withModel(strtolower($this->model));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id,UpdateCategoryRequest $request)
    {
       /// dd($request->all());
        $filleable = $request->only($this->filleable);
        if ($request->get('category_image')) $filleable['category_image'] = $request->get('category_image');
        $model = $this->model;
        $update = $this->$model->find($id);
        /* $update->fill($filleable);
        $update->save();*/
        $name=($request->get('name'));
        $update->title=$name[0];
        $update->category_order=$request->get('category_order');
        $update->status=$request->get('status');
        $update->lang_id=DEFAULT_LANGUAGE_CODE;
        if($update->save()){
            foreach ($lang=getLanguageList() as $key => $value) { //dd($value);
                $catDesc['category_id']=$id;
                $catDesc['name']=$name[$key];
                $catDesc['lang_id']=$value['code'];
                $update->afterSave($catDesc);
            }
            
        }

        Session::flash('success', $model. ' updated successfully.');
        return redirect()->route(strtolower($this->model).'.index');
    }

    public function status(Request $request,$slug)
    {   
        $model = $this->model;
        $id = $this->getRecord($slug, $id=true);
        if($id){
            $change = $this->$model->find($id);
            $active = $change->status;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                     $this->$model->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->$model->where('id', $id)
                        ->update($update_arr);
                }
                 $message = $model.' status changed successfully.';
                 $type = 'success';
            }else{
                 $message = 'You have something went wrong.';
                 $type = 'warning';
            }
             // return back();
        }else{
            $message = 'You have something went wrong.';
            $type = 'error';
        }
        if($request->ajax()){
            return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
        }
         return back();
         Session::flash($type, $message);   
    }

    /**
     * Remove the specified resource from storage.
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

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRecord($slug)
    {
       $model = $this->model;
       $slug = $this->$model->findBySlug($slug);
       if(empty($slug)) return false;
        return $slug->id;
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getList($id=NULL)
    {
       return Category::where('status',1)->pluck('title','id')->toArray();
    }
     /**
     * Returen the back of if error has accoured
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ValidateHeader()
    {
        Session::flash('warning', 'You have something went wrong');
         return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
