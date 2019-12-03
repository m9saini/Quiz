<?php

namespace Modules\EmailTemplates\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\EmailTemplates\Entities\EmailTemplate;
use DB,View,Session,Redirect,Mail,Config,Auth;
use App\Http\Controllers\AlertController;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use Modules\EmailTemplates\Http\Requests\EmailTemplateRequest;
use Modules\EmailTemplates\Http\Requests\UpdateEmailTemplateRequest;


class EmailTemplatesController extends Controller
{
     public function __construct(Request $request, EmailTemplate $EmailTemplate,AlertController $alertController)
    {
        $this->middleware('auth');
        $this->middleware('ability', ['except' => ['create','getAjaxIndex']]);
        $this->request = $request;
        $this->EmailTemplate = $EmailTemplate;
        $this->alert = $alertController;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('emailtemplates::index');
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
        $lists = $this->EmailTemplate->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                            ->get();  
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['email-templates.destroy', [$list->slug]], 'edit'=>['email-templates.edit', [$list->slug]],]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    return $edit;
                })  
                ->editColumn('body', function($list){
                    return substr(strip_tags($list->body), 0, $limit = 30) . $end = "...";
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
        return view('emailtemplates::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(EmailTemplateRequest $request)
    {
        $filleable = $request->only('slug','name','subject','body');
        $this->EmailTemplate->fill($filleable);
        $this->EmailTemplate->save();
        Session::flash('success', 'Email template created successfully.');
            return redirect()->route('email-templates.index');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($slug)
    {
        if($this->getEmail($slug) == false) return $this->ValidateHeader();
        $id = $this->getEmail($slug);
        $email = $this->EmailTemplate->findorFail($id);
        return view('emailtemplates::show',compact('email'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->EmailTemplate->findBySlug($id);
        return view('emailtemplates::edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id,UpdateEmailTemplateRequest $request)
    {
        $filleable = $request->only('name','subject','body');
        $email = $this->EmailTemplate->find($id);
        $email->fill($filleable);
        $email->save();
        Session::flash('success', 'Email template updated successfully.');
            return redirect()->route('email-templates.index');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $email = $this->EmailTemplate->find($id);
            $email->destroy($id);
            Session::flash('success', 'Email template deleted successfully.');
        }catch (QueryException $e){
            Session::flash('warning', 'You cannot delete this record yet,try again later');
        }
         return redirect()->route('email-templates.index');
    }

    public function changeStatus($slug)
    {   
       if($this->getEmail($slug) == false) return $this->ValidateHeader();
       $id = $this->getEmail($slug);
       if($id){
            $email = $this->EmailTemplate->find($id);
            $active = $email->is_active;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('is_active' => 0);
                     $this->EmailTemplate->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('is_active' => 1);
                    $this->EmailTemplate->where('id', $id)
                        ->update($update_arr);
                }
                 Session::flash('success', 'Email template status change successfully');             
            }else{
                Session::flash('warning', 'You have something went wrong');
            }
            return redirect()->route('email-templates.index');
        }else{
            Session::flash('warning', 'You have something went wrong');
        }
         return redirect()->route('email-templates.index');
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEmail($slug)
    {
       $slug = $this->EmailTemplate->findBySlug($slug);
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
