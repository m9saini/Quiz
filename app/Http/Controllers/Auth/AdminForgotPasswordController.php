<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Foundation\Auth\SendsAdminPasswordResetEmails;
use Password;
use Auth;
use Illuminate\Http\Request;

class AdminForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsAdminPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('guest:admin');
    }

    public function showLinkRequestForm() {
       
        if(Auth::user()){
            return redirect()->route('backend.dashboard');
        }
        return view('auth.passwords.admin-email');
    }

    //defining which password broker to use, in our case its the admins
    protected function broker() {
        return Password::broker('admins');
    }
}