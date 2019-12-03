<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AlertController;
use App\Models\Certified;
use Session;
use App\Models\Role;
use Auth,DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Backend\Access\User\CreateUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use App\Http\Requests\Backend\Access\User\MediaRequest;
use Modules\EmailTemplates\Entities\EmailTemplate;
use Mail;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user,  $request, $alert;
    public function __construct(User $user,Role $Role, Request $request,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' =>'saveMedia','BackReset','BackForgotPassword','getAjaxData']);
        $this->user=$user;
        $this->Role=$Role;
        $this->alert=$alertController;
        $this->request=$request;        
    }

    public function index($role=NULL)
    {
         return view('admin.user.index',compact('role'));
    }
    
    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getAjaxData(Request $request)
    { 
        try {  
        DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->user->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));      
        if( $request->role && !empty($request->role))
        {
            $lists = $lists->with('roles')->whereHas('roles', function($q) use ($request){$q->whereSlug($request->role);} );
        }            
        else
        {
            $lists = $lists->with('roles'); 
        }
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton( [ 'delete'=>['users.destroy', [$list->username]], 'deleteAjax'=>['users.destroy', [$list->username]], 
                        'edit'=>['users.edit', [$list->username]], 
                        getStatusAI($list->status)=>['users.status',[$list->username]],
                                'view'=>['users.show',[$list->username]]]);
                    $status =keyExist($dispalyButton, getStatusAI($list->status));
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                    $view = keyExist($dispalyButton, 'view');
                 //   $email ="&nbsp;&nbsp;&nbsp;<a href='mailto:$list->email' class='tooltips'  data-original-title='Send Email' ><i class='fa fa-envelope' aria-hidden='true'></i></a>";
                    if(!$list->hasRole('admin')){
                        return $view.$status.$edit.$delete;  
                    }                  
                     return $edit;  
                })
                ->addColumn('rolename', function ($list) {
                    return $list->roles->map(function($role) {
                        return $role->display_name;
                    })->implode(', ');
                })
                /* ->editColumn('available', function($list){
                    //return '';
                    return addClassForOnlineOffline($list->hasOnline());
                })*/
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function create($role=NULL)
    { 
         $role = $this->Role->findBySlug($role);
         $role_id = NULL;
         if($role) $role_id = $role->name;
         return view('admin.user.create', compact('role_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function store(CreateUserRequest $request)
    {
        //dd($request->all());
        //$request['username'] = $request['name'];
       // die('hh');
        $new_password = $request['password'];
        $request['password'] = trim(Hash::make($request['password']));
        $request['status'] = 1;
        $filleable = $request->only('name','username','email','status','password','phone','ph_country_id');  
        if ($request->get('image')) $filleable['image'] = $request['image'];
        if ($request->get('cover_image')) $filleable['cover_image'] = $request['cover_image'];
        $user = User::create($filleable);
        if($user){
           $role = $this->Role::findByName($request->role_id);
            if(!empty($role))
            {
                $user->roles()->sync([$role->id]);
            }
          /* $emailtemplate = EmailTemplate::where('slug','create-user')->first();
            $subject = $emailtemplate->subject;
            $body = $emailtemplate->body;
            $body = str_replace('[username]', $user->name, $body);
            $body = str_replace('[email]', $user->email, $body);
            $body = str_replace('[password]', $new_password, $body);
            $body = str_replace('[loginurl]', route('login'), $body);
            $data1 = [
                'content' => $body,
                'user' => $user,
                'subject' => $subject
            ];
            Mail::send('admin.emails.email-template', $data1, function ($message) use ($data1) {
                $message->to($data1['user']->email)->subject($data1['subject']);
            }); 
            */
             $request->session()->flash('success', 'User has been added successfully.');  
             return redirect()->route('users.index');
        }else{
            $request->session()->flash('error', 'Sorry User has not been added successfully.');  
            return back();
        }      
    }
    

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function show($slug)
    { 
        try
        { 
            $user=$this->getValue($slug);
            if($user)
                return view('admin.user.show',compact('user'));
            return redirect()->route('users.index');
        }
        catch (\Exception $e)
        {
            return redirect()->route('roles.index')->$this->request->session()->flash('error', 'Something went wrong with role model');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function edit($slug)
    {
        try
        {
            $user = $this->user->whereUsername($slug)->with('roles')->first();

            $user =$this->getValue($slug);
            $roleSlug = getAllList('roles', 'id', 'slug');
            $roleList = getAllList('roles', 'id', 'display_name');
            return view('admin.user.edit',compact(['user', 'roleList', 'roleSlug']));
        }
        catch (\Exception $e)
        {
            Session::flash('warning', 'Either something went wrong or invalid access');
            return redirect()->route('users.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function update(Request $request, $slug)
    {
        $id=$this->getValue($slug, $id=true);
        $value = $this->getValue($slug);
        $this->validate($request, $this->UpdateUsersRules($id), $this->validationErrorMessages());
       try{
            $request['username'] = isset($request['username']) ? $request['username'] : $request['email'];
            $request['status'] = 1;
             $filleable = $request->only('name','username','email','status','phone','ph_country_id'); 
            if($request->get('password')){
                $filleable['password'] = trim(Hash::make($request['password']));
            }
            if ($request->get('image')) $filleable['image'] = $request['image'];
            if ($request->get('cover_image')) $filleable['cover_image'] = $request['cover_image'];
            $user = $this->user->findorFail($id);
            $user->update($filleable);
            if($user){
                $role = $this->Role::findByName($request->role_id);
                if(!empty($role) && $request->role_id != 'admin')
                {
                    $user->roles()->sync([$role->id]);
                }
                $request->session()->flash('success', 'User has been updated successfully.');
                return redirect()->route('users.index'); 
            }else{
               $request->session()->flash('error', 'Sorry User has not been updated successfully.');  
                return back(); 
            }  
           
        }catch (\Exception $e)
        {
            Session::flash('warning', 'Either something went wrong or invalid access');
            return redirect()->route('users.index');
        }           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     protected function destroy(Request $request,$slug)
    {
        try
        {
           $user = $this->user->whereUsername($slug)->first();
           if($user->hasRole('admin')){
            $type = 'warning'; $message = 'You cannot delete this user (Administrator).';
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
            }
            $request->session()->flash($type, $message);
                return back();
            }
            //$user->delete();
            $user->deleted_at = Carbon::now();
            $user->save();
            $type = 'success'; $message = 'User has been deleted successfully.';
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
            }
            $request->session()->flash($type, $message);
                return back();
        }
        catch (\Exception $e)
        {
            $type = 'warning'; $message = 'Either something went wrong or invalid access.';
            if($request->ajax()){
                return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
            }
            $request->session()->flash($type, $message);
                return back();
        }
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function getValue($slug, $id=false)
    {
        $data= $this->user->whereUsername($slug)->with('roles')->first();
        if(!$data) return back('users.index');
        if($id==true) return $data->id;
        return $data;
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function getUserInfo($slug)
    {
        $data= $this->user->where('deleted_at',NULL)->orWhere('username',$slug)->orWhere('id',$slug)->with('roles')->first();
        return $data;
    }

    protected function getValueBySlug($slug, $id=false)
    {
        $data= $this->user->whereSlug($slug)->with('roles')->first();
        if(!$data) return back('users.index');
        if($id==true) return $data->id;
        return $data;
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function deleted($role=NULL)
    {
        return view('admin.user.deleted_user',compact('role'));
    }

        /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeletedAjaxData(Request $request)
    { 
        try {  
        DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->user->onlyTrashed()->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));      
        $lists = $lists->with('roles'); 
        return Datatables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['users.directDelete', [$list->slug]], 
                        'restore'=>['users.restoreBack', [$list->slug]]]);
                    $restore = keyExist($dispalyButton, 'restore');
                    $delete = keyExist($dispalyButton, 'delete');
                        return $restore;                    
                })
                ->addColumn('rolename', function ($user) {
                    return $user->roles->map(function($role) {
                        return $role->display_name;
                    })->implode(', ');
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

    /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function inactivated($role=NULL)
    {
        return view('admin.user.inactivated_user',compact('role'));
    }
   

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getInactivatedAjaxData(Request $request)
    { 
        try {  
        DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->user->where('status',0)->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));      
        if( $request->role && !empty($request->role))
        {
            $lists = $lists->with('roles')->whereHas('roles', function($q) use ($request){$q->whereSlug($request->role);} );
        }            
        else
        {
            $lists = $lists->with('roles'); 
        }
        return Datatables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton( ['deleteAjax'=>['users.destroy', [$list->username]], 
                        'edit'=>['users.edit', [$list->username]], 
                        getStatusAI($list->status)=>['users.status',[$list->username]]]);
                     $status =keyExist($dispalyButton, getStatusAI($list->status));
                      $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $status.$edit.$delete;                    
                })
                ->addColumn('rolename', function ($user) {
                    return $user->roles->map(function($role) {
                        return $role->display_name;
                    })->implode(', ');
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }

       /**
     * Returen the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function activated($role=NULL)
    {
        return view('admin.user.activated_user',compact('role'));
    }

    /**
     * Return a data-table listing of the resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function getActivatedAjaxData(Request $request)
    { 
        try {  
        DB::statement(DB::raw('set @rownum=0'));
        $lists = $this->user->where('status',1)->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'));      
        if( $request->role && !empty($request->role))
        {
            $lists = $lists->with('roles')->whereHas('roles', function($q) use ($request){$q->whereSlug($request->role);} );
        }            
        else
        {
            $lists = $lists->with('roles'); 
        }
        return Datatables::of($lists)
                ->addColumn('action', function($list){
                  $dispalyButton = displayButton( ['deleteAjax'=>['users.destroy', [$list->username]], 
                        'edit'=>['users.edit', [$list->username]], 
                        getStatusAI($list->status)=>['users.status',[$list->username]]]);
                     $status =keyExist($dispalyButton, getStatusAI($list->status));
                      $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'deleteAjax');
                        return $status.$edit.$delete;                      
                })
                ->addColumn('rolename', function ($user) {
                    return $user->roles->map(function($role) {
                        return $role->display_name;
                    })->implode(', ');
                })
                ->make(true);
        } 
        catch (Exception $ex) {
            return false;
        }        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    protected function RestoreUser($slug)
    {
        try
        {
            $user = $this->user->whereSlug($slug)->restore();
            $this->request->session()->flash('success', 'User has been restored successfully.');
            return redirect()->route('users.deleted');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('users.deleted');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    protected function parmanent_destroy($slug)
    {
        try
        {
            $this->user->whereSlug($slug)->forceDelete();
            $this->request->session()->flash('success', 'User has been deleted successfully.');
            return redirect()->route('users.deleted');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */

    public function status(Request $request,$slug)
    {   
        $id = $this->getValue($slug, $id=true);
        if($id){
            $change = $this->user->find($id);
            $active = $change->status;
            if ($id != null) 
            {
                if($active==1)
                {
                    $update_arr = array('status' => 0);
                     $this->user->where('id', $id)->update($update_arr);
                }
                else
                { 
                    $update_arr = array('status' => 1);
                    $this->user->where('id', $id)
                        ->update($update_arr);
                }
                 $message = 'User status changed successfully.';
                 $type = 'success';
            }else{
                 $message = 'You have something went wrong.';
                 $type = 'warning';
            }
             //return back();
        }else{
             $message = 'You have something went wrong.';
             $type = 'warning';
        }
        if($request->ajax()){
            return response()->json(['status_code'=> 200, 'type'=>$type,'message' => $message]);
        }
         return back();
         Session::flash($type, $message);     
    }

    
    /**
     * View user profile update form Auth User
     *
     * @param  With Auth User
     */
    protected function updateProfile()
    {
        try
        {
            return view('admin.user.update_profile')->withUser(auth()->user());
        }
        catch (\Exception $e)
        {
            Session::flash('warning', 'Either something went wrong or invalid access');
            return redirect()->route('backend.dashboard');
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function storeUpdateProfile(UpdateUserRequest $request,$id)
    {
        try
        {
            $this->user->UpdateUser($request,$id); 
            Session::flash('success', 'User has been updated successfully.');
            return redirect()->route('users.index');            
        }
        catch (\Exception $e)
        {
            Session::flash('warning', 'Either something went wrong or invalid access');
            return redirect()->route('users.index');
        }
    }

    /**
     * Show change Password form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function changePassword()
    {
        try
        {
            return view('admin.user.change_password');
        }
        catch (\Exception $e)
        {
            Session::flash('warning', 'Either something went wrong or invalid access');
            return redirect()->route('backend.dashboard');
        }
    }

    /**
     *  Update Login User Password
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function storeChangePassword(Request $request)
    {
            $this->validate($request, $this->rules(), $this->validationErrorMessages());
            if (Hash::check($request->old_password, auth()->user()->password)) {
                $this->resetPassword(auth()->user(), $request->password);
                //Send confirmation email on password change for account user
                /*
                $emailtemplate = EmailTemplate::where('slug','password-change-confirmation')->first();
                $subject = $emailtemplate->subject;
                $body = $emailtemplate->body;
                $body = str_replace('[username]', auth()->user()->name, $body);
                $data1 = [
                    'content' => $body,
                    'user' => auth()->user(),
                    'subject' => $subject
                ];
                Mail::send('admin.emails.email-template', $data1, function ($message) use ($data1) {
                    $message->to($data1['user']->email)->subject($data1['subject']);
                });             
                */
                 if ($request->ajax()) {
                    return response()->json(['status_code'=> 200, 'type'=>'success','reset'=>true,'message' => 'Your password has been changed.']);
                }  
                $request->session()->flash('success', 'Your password has been changed.');
                return back();
            }
            else
            {
                  if ($request->ajax()) {
                        return response()->json(['status_code'=> 500, 'type'=>'error','reset'=>false, 'message' => 'That is not your old password.']);
                    }  
                $request->session()->flash('error', 'That is not your old password.');
                return redirect()->back(); 
            }
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
           $filename = upload($request->file('files'),storage_path() . '/app/public/users/');
           if($request->get('user_id')){
            $user = $this->user->find($request->get('user_id'));
            $user->image = $filename;
            $user->save();
           }
            return response()->json(["status"=>true,"filename"=>$filename]); 
        }catch (\Exception $e) {
              return response()->json(["status"=>false,"message"=>$e->getMessage()]);
        }  
    }

    /**
     * Reset the password with user data.
     *
     * @return array
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

     /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:6',
            'old_password'=>"required"
        ];
    }

     /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function UpdateUsersRules($id)
    {
        return [
            'name' => 'required',
            'email'=>"required|unique:users,email,$id",
            'username'=>"required|regex:/^\S*$/u|unique:users,username,$id",
            'role_id'=>'required',
            'password_confirmation' => 'required_with:password|same:password',
            //'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=500',
            //'cover_image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=800,min_height=500',
        ];
    }
    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

}
