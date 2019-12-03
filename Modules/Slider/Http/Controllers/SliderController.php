<?php

namespace Modules\Slider\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Slider\Entities\Slider;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\Backend\Access\User\MediaRequest;
use Modules\Slider\Http\Requests\CreateSliderRequest;
use Modules\Slider\Http\Requests\UpdateSliderRequest;


class SliderController extends Controller
{
     /**
     * Dyanamic Controller model and function
     * Change $model = '' according to your module
     * This work only when model name & routing name is same
     * Change Fillable params according to your tables
     **/
     protected $model = 'Slider';
     protected $filleable = ['slug','description','slider_order'];
     public function __construct(Request $request, Slider $Slider,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['create','ajaxdata']]);
        $this->request = $request;
        $this->Slider = $Slider;
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
                    ->editColumn('banner_image', function($list){ 
                            return '<a class="group1" href="'.$list->picture_path.'" title="'.str_limit(strip_tags($list->description), 300).'"><img src="'.$list->picture_path.'" class="img-thumbnail" alt="" width="120" id="" ></a>';
                    })
                    ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
                    })
                    ->rawColumns(['banner_image', 'action'])
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
        return view(strtolower($this->model).'::create')->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateSliderRequest $request)
    {
        $filleable = $request->only($this->filleable);
        $filleable['slug'] = time();
        if ($request->get('banner_image')) $filleable['banner_image'] = $request->get('banner_image');
        $model = $this->model;
        $this->$model->fill($filleable);
        $this->$model->save();
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
        return view(strtolower($this->model).'::edit',compact('data'))->withModel(strtolower($this->model));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id,UpdateSliderRequest $request)
    {
        $filleable = $request->only($this->filleable);
        if ($request->get('banner_image')) $filleable['banner_image'] = $request->get('banner_image');
        $model = $this->model;
        $update = $this->$model->find($id);
        $update->fill($filleable);
        $update->save();
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