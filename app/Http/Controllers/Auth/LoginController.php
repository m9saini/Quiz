<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth,Session,URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
    */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct(User $User)
    {
        $this->middleware('guest')->except(['logout']);
        $this->User = $User;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        $redirectUrl = env('FACEBOOK_REDIRECT_CALLBACK');
        return Socialite::driver('facebook')->redirectUrl($redirectUrl)->redirect();
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProviderGoogle(Request $request)
    {
        $redirectUrl = env('GOOGLE_REDIRECT_CALLBACKS');
        return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
    }

       /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request,$provider)
    {
        if($request->get('error_code') == 200)  return redirect()->to('login')
                ->with('warning',"You cancelled successfully.");
        $user = Socialite::driver($provider)->user();
        $authUser = $this->checkAndUpdateIfAlreadyRegisteredBySocial($user,$provider);
        if(!$authUser){
              if(!$user->email){
                 return redirect()->route('register', ['name' => $user->name])
                ->with('warning','Email not found by '.$provider);
            }
             $authUser = $this->findOrCreateUser($user, $provider);  
         }
          if($authUser->deactivated){
                 $message = 'This account was deactivated. If you feel this was done in error, please contact our support team.';
                 $request->session()->flash('error', $message);
                    return redirect()->route('register', ['login' => 'true']);
            }
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $search_provider = 'provider_id';
        $userByEmail = User::where('email', $user->email)->first();
         if ($userByEmail) {
            $userByEmail->$search_provider = $user->getId();
            $userByEmail->provider = $provider;
            $userByEmail->save();
            return $userByEmail;
        }
        
        //$user->getNickname();
        //$user->getAvatar();
        $user =  User::create([
            //'first_name'     => $user->name,
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),
            'provider' => $provider,
            'is_social_login' => 1,
            'email_verified_at' => now(),
             $search_provider => $user->getId(),
            'is_activated' => 1,
            'password' => bcrypt(trim($user->id)),
        ]);
      
       return $this->User->assignRoleOnRegister($user->id);
    }

    /**
     * If a user has already registered with using social auth, update and return the user
     *  update user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function checkAndUpdateIfAlreadyRegisteredBySocial($user,$provider)
    {
        $search_provider = 'provider_id';
        $authUser = $this->User->where($search_provider, $user->id)->where('provider',$provider)->first();
        if($authUser){
            $authUser->name = $user->getName();
            $authUser->is_social_login = 1;
            $authUser->save();
        }
        return $authUser;
    }
}
