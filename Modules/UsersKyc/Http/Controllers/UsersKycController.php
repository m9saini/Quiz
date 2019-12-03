<?php

namespace Modules\UsersKyc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UsersKyc;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;

class UsersKycController extends Controller
{
    /**
     * Dyanamic Controller model and function
     * Change $model = '' according to your module
     * This work only when model name & routing name is same
     * Change Fillable params according to your tables
     **/
    protected $model = 'UsersKyc';
    protected $filleable = ['slug','question','answer','faq_order'];
    public function __construct(Request $request, UsersKyc $UsersKyc,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['getAjaxData']]);
        $this->request = $request;
        $this->UsersKyc = $UsersKyc;
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
                        $dispalyButton = displayButton(['deleteAjax'=>[strtolower($model).'.destroy', [$list->id]], 'edit'=>[strtolower($model).'.edit', [$list->id]],getStatusAI($list->status)=>[strtolower($model).'.status',[$list->id]],'view'=>['users.show',[$list->user_id]] ] );
                        $status =keyExist($dispalyButton, getStatusAI($list->status));
                        $edit = keyExist($dispalyButton, 'edit');
                        $delete = keyExist($dispalyButton, 'deleteAjax');
                        $view = keyExist($dispalyButton, 'view');
                        return $status.$delete;
                    })  
                     ->editColumn('doc_image', function($list){
                            return '<a class="group1" href="'.$list->picture_path.'" title="'.str_limit(strip_tags($list->doc_image), 300).'"><img src="'.$list->picture_path.'" class="img-thumbnail" alt="" width="120" id="" ></a>';
                    })  
                     ->editColumn('kyc_type', function($list){
                            return $list->kyc_fullname;
                    })
                    ->editColumn('created_at', function($list){
                        return date_format($list->created_at,"m/d/Y");
                    })
                   ->rawColumns(['doc_image', 'action'])
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
    public function store(CreateFaqRequest $request)
    {
        //dd($request->all());
        $filleable = $request->only($this->filleable);
        $filleable['slug'] = time();
        /*$model = $this->model;
        $this->$model->fill($filleable);
        $this->$model->save();*/
        $question=($request->get('question'));
        $answer=($request->get('answer'));
        $faq= new Faq();
        $faq->question=$question[0];
        $faq->answer=$answer[0];
        $faq->lang_id=DEFAULT_LANGUAGE_CODE;
        $faq->faq_order=$request->get('faq_order');
        if($faq->save()){

            foreach ($lang=getLanguageList() as $key => $value) { //dd($value);
                $faqDesc['faq_id']=$faq->id;
                $faqDesc['question']=$question[$key];
                $faqDesc['answer']=$answer[$key];
                $faqDesc['lang_id']=$value['code'];
                $faq->afterSave($faqDesc);
            }
        }
        Session::flash('success', $this->model.' created successfully.');
        return redirect()->route(strtolower($this->model).'.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        if($this->getUserKyc($id) == false) return $this->ValidateHeader();
        $data = $this->Faq->findorFail($id);
        return view('faq::show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->model;
        $data = $this->$model->find($id);
        return view(strtolower($this->model).'::edit',compact('data'))->withModel(strtolower($this->model));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id,UpdateFaqRequest $request)
    {
        $filleable = $request->only('question','answer','faq_order');
        /*$update = $this->Faq->find($id);
        $update->fill($filleable);
        $update->save();*/
        //dd($request);
        $question=($request->get('question'));
        $answer=($request->get('answer'));
        $faq= Faq::find($id);
        $faq->question=$question[0];
        $faq->answer=$answer[0];
        $faq->faq_order=$request->get('faq_order');
        $faq->lang_id=DEFAULT_LANGUAGE_CODE;
        if($faq->save()){

            foreach ($lang=getLanguageList() as $key => $value) { //dd($value);
                $faqDesc['faq_id']=$faq->id;
                $faqDesc['question']=$question[$key];
                $faqDesc['answer']=$answer[$key];
                $faqDesc['lang_id']=$value['code'];
                $faq->afterSave($faqDesc);
            }
        }
        Session::flash('success', 'FAQ updated successfully.');
        return redirect()->route('faq.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Request $request,$slug)
    {
        try{
            $model = $this->model;
            $delete = $this->$model->find($slug);
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
    public function getUserKyc($id)
    {
       $rst = $this->UsersKyc->find($id);
       if(empty($rst)) return false;
        return $rst->id;
    }

     public function status(Request $request,$id)
    {   
        $model = $this->model;
        $id = $this->getUserKyc($id);
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