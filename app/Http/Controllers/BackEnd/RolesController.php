<?php
namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Models\Permission;
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Input;
use DB;
use DataTables;

class RolesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $role, $request;
    public function __construct(Role $role, Request $request,AlertController $alert)
    {       
        $this->middleware('auth');  
        $this->middleware('ability', ['except' => ['']]);
        $this->role=$role;
        $this->request=$request;
        $this->alert=$alert;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function index()
    {        
        $roles=$this->role->all();
        return view('admin.user.role.index',compact('roles'));
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
        $lists = $this->role->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                            ->get();  
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['roles.destroy', [$list->slug]], 'edit'=>['roles.edit', [$list->slug]],'permission'=>['roles.premission.create', [$list->slug]]]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    $permission = keyExist($dispalyButton, 'permission');
                    return $edit.$permission;
                })  
                ->editColumn('description', function($list){
                    return str_limit(strip_tags($list->name), $limit = 30, $end = '...');
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
     *
     * @return \Illuminate\Http\Response
     */
    protected function create()
    {
        return view('admin.user.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function store(Request $request)
    {
        $this->request->name = strtolower($this->request->name);
        $this->validate($this->request,
            [
                'name'=>'required|Alpha|unique:roles',
                'display_name'=>'required',
                'description'=>'required'
            ]);
        try
        {

            $this->role->handle($this->request);
            $this->request->session()->flash('success', 'Role has been added successfully.');
            return redirect()->route('roles.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    protected function show($slug)
    { 
        try
        {
            $role=$this->role->findBySlug($slug);
            return view('admin.user.role.edit',compact('role'));
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
            $role =$this->getValue($slug);
            return view('admin.user.role.edit',compact('role'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function update($slug)
    {
        $id=$this->getValue($slug, $id=true);
        $this->request->name = strtolower($this->request->name);
        $this->validate($this->request,
            [
                'name'=>"required|Alpha|unique:roles,id,$id",
                'display_name'=>'required',
                'description'=>'required'
            ]);
        try
        {
            $this->role->UpdateRole($this->request,$id);
            $this->request->session()->flash('success', 'Role has been updated successfully.');
            return redirect()->route('roles.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function destroy($slug)
    {
        try
        {
            $this->role->whereSlug($slug)->delete();
            $this->request->session()->flash('success', 'Role has been deleted successfully.');
            return redirect()->route('roles.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
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
            $this->role->whereSlug($slug)->forceDelete();
            $this->request->session()->flash('success', 'Role has been deleted successfully.');
            return redirect()->route('roles.restore');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.restore');
        }

    }

    /**
     * Return the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function getValue($slug, $id=false)
    {
        $data= $this->role->findBySlug($slug);
        if(!$data) return back('roles');
        if($id==true) return $data->id;
        return $data;
    }

    /**
     * Return the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    protected function restore()
    {
        $roles=$this->role->onlyTrashed()->get();
        return view('admin.user.role.restore',compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    protected function RestoreRole($slug)
    {
        try
        {
            $role = $this->role->whereSlug($slug);
            $role->restore();
            $this->request->session()->flash('success', 'The role has been restored successfully.');
            return redirect()->route('roles.index');
        }
        catch (\Exception $e)
        {
            Log::info('Either something went wrong or invalid access Role Model - function update - line 202');
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
    protected function status($slug,$status)
    {
        if($status!=0 and $status !=1) return redirect()->route('role.view');
        try
        {
            $id=$this->getValue($slug, $id=true);
            $this->role->RoleStatus($id,$status);
            $this->request->session()->flash('success', 'Role has been updated successfully.');
            return redirect()->route('roles.index');
        }
        catch (\Exception $e)
        {
            Log::info('Either something went wrong or invalid access Role Model - function update - line 202');
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
        }
    }
    
    public function getPermission($slug)
    {
        $permission = Permission::all();
        $groupByPermission = $permission->groupBy('group_name');
        try {            
            $roles = $this->getValue($slug);
            $role = $this->role->whereSlug($slug)->with('Permissions')->first();
            return view('admin.user.role.permission', compact('role', 'groupByPermission'));
        } catch (\Exception $ex) {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('roles.index');
        }        
    }
    
    public function postPermission($slug)
    {
        try {
            $roles = $this->getValue($slug);
            if(isset($this->request->permission_id))
            {
                $role = $this->role->whereSlug($slug)->first();
                $role->Permissions()->sync($this->request->permission_id);
            }            
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
        }        
    }

}
