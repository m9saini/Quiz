<?php

namespace Modules\Configuration\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Configuration\Entities\Configuration;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use Modules\Configuration\Http\Requests\CreateConfigurationRequest;
use Modules\Configuration\Http\Requests\UpdateConfigurationRequest;

class ConfigurationController extends Controller
{
     /**
     * Dyanamic Controller model and function
     * Change $model = '' according to your module
     * This work only when model name & routing name is same
     * Change Fillable params according to your tables
     **/
    protected $model = 'Configuration';
    protected $filleable = ['slug', 'config_title', 'config_value'];
    public function __construct(Request $request, Configuration $Configuration,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['getAjaxData']]);
        $this->request = $request;
        $this->Configuration = $Configuration;
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
                        $dispalyButton = displayButton(['delete'=>[strtolower($model).'.destroy', [$list->slug]], 'edit'=>[strtolower($model).'.edit', [$list->slug]],]);
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'delete');
                        return $edit.$delete;
                    })  
                ->editColumn('created_at', function($list){
                    return date_format($list->created_at,"m/d/Y");
                })
                 ->editColumn('config_value', function($list){
                    return str_limit(strip_tags($list->config_value), 300);
                })
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
    public function store(CreateConfigurationRequest $request)
    {
        $filleable = $request->only($this->filleable);
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
    public function update($id,UpdateConfigurationRequest $request)
    {   
        $filleable = $request->only($this->filleable);
        $model = $this->model;
        $update = $this->$model->find($id);
        $update->fill($filleable);
        $update->save();
        Session::flash('success', $model. ' updated successfully.');
        return redirect()->route(strtolower($this->model).'.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($slug)
    {
        try{
            $model = $this->model;
            $delete = $this->$model->findBySlug($slug);
            $delete->destroy($delete->id);
            Session::flash('success', $model.' deleted successfully.');
        }catch (QueryException $e){
            Session::flash('warning', 'You cannot delete this record yet,try again later');
        }
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
}
