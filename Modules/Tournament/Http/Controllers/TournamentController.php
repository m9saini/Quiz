<?php

namespace Modules\Tournament\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Tournament;
use App\Category;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use Modules\Tournament\Http\Requests\CreateTournamentRequest;

class TournamentController extends Controller
{
    protected $model = 'Tournament';
    protected $filleable = ['title','joinfees','size','type','start_time','game_shedule','win_amount_type','win_amount','cat_id','is_repeated'];

    public function __construct(Request $request, Tournament $Tournament,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['create','ajaxdata']]);
        $this->request = $request;
        $this->Tournament = $Tournament;
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
                    ->editColumn('is_repeated', function($list){
                        return ($list->is_repeated)?'Yes':'No';
                    })
                   ->editColumn('start_time', function($list){
                        return $list->start_time.' ('.getTournamentShedule($list->game_shedule).')';
                    })
                   ->editColumn('win_amount', function($list){
                        return $list->win_amount.' ('.getWinningAmountType($list->win_amount_type).')';
                    })
                    ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
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
        $catList=$this->getCategoryList();
        $gsh= Input::old('game_shedule');
        if($gsh=='w')
            $weekdayfield='block';
        else
            $weekdayfield='none';
        return view(strtolower($this->model).'::create', compact('catList','weekdayfield'))->withModel(strtolower($this->model));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(CreateTournamentRequest $request)
    {

        $filleable = $request->only($this->filleable);
        $model= new Tournament();
        $model->lang_id=DEFAULT_LANGUAGE;
        $model->fill($filleable);
        $model->save();
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
        return view('tournament::show');
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
        $catList=$this->getCategoryList();
        $gsh= Input::old('game_shedule');
        if($gsh=='w')
            $weekdayfield='block';
        else
            $weekdayfield='none';
        return view(strtolower($this->model).'::edit',compact('data','catList','weekdayfield'))->withModel(strtolower($this->model));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(CreateTournamentRequest $request, $id)
    {
        $filleable = $request->only($this->filleable);
        $model = $this->model;
        $model = $this->$model->find($id);
        $model->fill($filleable);
        $model->save();
        Session::flash('success', $this->model.' created successfully.');
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
    public function getCategoryList($id=NULL)
    {
       return Category::where('status',1)->pluck('title','id')->toArray();
    }
}
