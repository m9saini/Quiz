<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
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
        //return view('home');
    }
}
