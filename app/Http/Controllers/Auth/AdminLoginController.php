<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getAdminLogin()
    {
        if (auth()->user()) return redirect()->route('backend.dashboard');
        return view('auth.adminLogin');
    }

    public function adminAuth(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            //'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($this->guard()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            if(Auth::user()->hasRole('admin')){
                $user=User::find(Auth::user()->id);
                $user->lastlogin=date('Y-m-d H:i:s');
                if(Auth::user()->roles[0]->name == 'admin'){
                    $user->save();
                    return redirect()->route('backend.dashboard');
                }else{
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return redirect('admin/login');
                }
            }else{
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->flash('error', 'This User not assign any role.');
                return redirect('admin/login');
            }
        }else{
              $request->session()->flash('error', 'Your username and password are wrong.');
                return redirect()->back();
        }
    }
}
