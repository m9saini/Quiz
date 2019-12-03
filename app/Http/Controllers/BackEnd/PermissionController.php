<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionGroups;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Route;
use DB;
use DataTables;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $permission,$permissionGroups,$request, $alert;
    public function __construct(Permission $permission,PermissionGroups $permissionGroups, Request $request, AlertController $alertController)
    {
        $this->middleware('auth');
        $this->middleware('ability', ['except' => ['create','getAjaxIndex']]);
        $this->permission = $permission;
        $this->permissionGroups = $permissionGroups;
        $this->request = $request;
        $this->alert = $alertController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function index()
    {
        $permissions=$this->permission->all(); 
        return view('admin.user.permission.index',compact('permissions'));
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
        $lists = $this->permission->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                            ->get();       
        return DataTables::of($lists)
                ->addColumn('action', function($list){
                    $dispalyButton = displayButton(['delete'=>['permission.destroy', [$list->slug]], 'edit'=>['permission.edit', [$list->slug]]]);
                    $edit = keyExist($dispalyButton, 'edit');
                    $delete = keyExist($dispalyButton, 'delete');
                    return $edit;
                })         
                ->editColumn('description', function($list){
                    return substr(strip_tags($list->description), 0, $limit = 30) . $end = "...";
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
        $routes = $this->getRoute();
        foreach($routes as $ro)
        {
            if(isset($ro['as']))
            {
                //create permission groups auto
                $group = $this->permissionGroups->createPermissionGroup($ro['as']);
                //create permissions by routing
                $count = $this->permission->where('name',$ro['as'])->count();
                if($count == 0)
                {
                    $array['name'] = $ro['as'];
                    $array['group_name'] = $group;
                    $array['display_name'] = $ro['as'];
                    $array['description'] = $ro['as'];
                    $this->permission->handleSubmit($array);
                }                
            }
        }
        session()->flash('success', 'Permission has been successfully reloaded.');
        return redirect()->route('permission.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function store()
    {
        $this->validate($this->request,
            [
                'name'=>'required|unique:permissions',
                'display_name'=>'required',
                'description'=>'required',
                'group_name'=>'required'
            ]);
        try
        {
            $this->permission->handle($this->request);
            $this->request->session()->flash('success', 'Permission has been added successfully.');
            return redirect()->route('permission.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('permission.index');
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
            $role=$this->role->findBySlug($slug);
            return view('admin.user.permission.edit',compact('permission'));
        }
        catch (\Exception $e)
        {
            return redirect()->route('permission.index')->$this->request->session()->flash('error', 'Something went wrong with role model');
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
            $permission =$this->getValue($slug); 
            $routes = $this->permission->getPermissionRouteLists();
            return view('admin.user.permission.edit',compact('permission','routes'));
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('permission.index');
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
        $this->validate($this->request,
            [
                'display_name'=>'required',
                'description'=>'required',
                'group_name'=>'required'
            ]);
        try
        {
            $aa = $this->permission->UpdatePermission($this->request,$id);              
            $this->request->session()->flash('success', 'Permission has been updated successfully.');
            return redirect()->route('permission.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('permission.index');
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
            $this->permission->whereSlug($slug)->delete();
            $this->request->session()->flash('success', 'Permission has been deleted successfully.');
            return redirect()->route('permission.index');
        }
        catch (\Exception $e)
        {
            $this->request->session()->flash('warning', $this->alert->warning());
            return redirect()->route('permission.index');
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
        $data= $this->permission->findBySlug($slug);
        if(!$data) return back('permission.index');
        if($id==true) return $data->id;
        return $data;
    }

    

    protected function getRoute()
   {
       $action = [];
       foreach (Route::getRoutes()->getRoutes() as $route)
       {
           $action[] = $route->getAction();           
       }
       return $action;

   }

}
