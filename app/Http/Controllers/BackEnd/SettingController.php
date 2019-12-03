<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Modules\Category\Entities\Category;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AlertController;
use Session;
use App\Models\Role;
use DB,Auth,Redirect;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $setting,$request, $alert;
    public function __construct(Setting $setting, Request $request,AlertController $alertController)
    {
        $this->middleware(['auth','ability'], ['except' => ['getAjaxData']]);
        $this->Setting=$setting;
        $this->alert=$alertController;
        $this->request=$request;        
    }

    public function index()
    {
        $sdata=Setting::where('status',1)->get(); 
        return view('admin.setting.index',compact('sdata'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        if(!empty($request)){
            $message    =   array(
                        'commission.required|numeric'       =>  "Please enter commission.",
                        //'language.required'  =>  "Please select language.",
                    );
            $validate   =   array(
                    'commission'    =>  'required|numeric',
                    //'language'    =>  'required',

                );
                if ($this->validate($request,$validate,$message)) {
                foreach ($request->all() as $key => $value) {
                $model= Setting::findByKeyName($key);
                    if($model){
                        $model->key_value=$value;
                        $model->save();
                    }
                }
                Session::flash('success', 'Settings updated successfully.');
                return Redirect::route("backend.settings");
            }
        }
    }

}
