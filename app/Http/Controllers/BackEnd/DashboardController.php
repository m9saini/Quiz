<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Modules\Category\Entities\Category;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AlertController;
use Session;
use App\Models\Role;
use DB,Auth;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user,  $request, $alert;
    public function __construct(User $user,/*Category $Category,*/ Request $request,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['getAjaxData']]);
        $this->user=$user;
       // $this->Category=$Category;
        $this->alert=$alertController;
        $this->request=$request;        
    }

    public function index($role=NULL)
    {
         $allUser = $this->user->all();
        // $category = $this->Category->get();
         $user = $this->user->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with('roles')->whereHas('roles', function($q){$q->whereSlug('user');})->get();
         $manager = $this->user->select('*', DB::raw('@rownum  := @rownum  + 1 AS rownum'))->with('roles')->whereHas('roles', function($q){$q->whereSlug('manager');})->get();
         return view('admin.dashboard.index',compact('role','allUser','user','manager','category'));
    }

}
