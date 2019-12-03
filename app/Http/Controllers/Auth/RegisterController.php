<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Foundation\Auth\RegistersUsers;
//use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $User,  $Role;

    public function __construct(Role $Role,User $User)
    {
        $this->middleware('guest');
        $this->Role = $Role;
        $this->User = $User;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = $this->User->createUser($data);
        $u = $user->toArray();
        $u['link'] = str_random(30);
        /*
        DB::table('user_activations')->insert(['id_user'=>$u['id'],'token'=>$u['link']]);
        $emailtemplate = EmailTemplate::where('slug','activate-user')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', $user->name, $body);
        $body = str_replace('[activationlink]', url('/') . '/user/activation/'.$u['link'], $body);
        $data1 = [
            'content' => $body,
            'user' => $user,
            'subject' => $subject
        ];
        Mail::send('admin.emails.email-template', $data1, function ($message) use ($data1) {
            $message->to($data1['user']->email)->subject($data1['subject']);
        });    
        */ 
        return $user;
    }
}
