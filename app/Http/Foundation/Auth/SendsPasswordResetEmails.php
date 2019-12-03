<?php

namespace  App\Http\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Mail;
use App\User;
use Illuminate\Support\Str;
use Modules\EmailTemplates\Entities\EmailTemplate;

trait SendsPasswordResetEmails
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return redirect('/');
       // return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $response = 'passwords.user';
        $user = User::where('email',$request->only('email'))->first();
      
        if(count($user)>0 && $user->hasRole('admin') == false ){
            $token = Password::getRepository()->create($user);
            $this->changeresetLinkToken($user,$token);
            $resetlink =  route('password.reset', $token);
            
            $emailtemplate = EmailTemplate::where('slug','forgot-password')->first();
            $subject = $emailtemplate->subject;
            $body = $emailtemplate->body;
            $body = str_replace('[username]', $user->name, $body);
            $body = str_replace('[resetlink]', '<a href="'.$resetlink.'">'.$resetlink.'</a>', $body);
            $data1 = [
                'content' => $body,
                'user' => $user,
                'subject' => $subject
            ];
            Mail::send('admin.emails.email-template', $data1, function ($message) use ($data1) {
                $message->to($data1['user']->email)->subject($data1['subject']);
            });    
            $response = 'passwords.sent';   
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
     
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

     /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
       //$this->guard()->login($user);
    }

    public function changeresetLinkToken($user,$token)
    {
          $user->forceFill([
                'remember_token' => $token,
            ])->save();
        return $user->remember_token;
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request,$response)
    {
         if ($request->ajax()) {
                return response()->json(['status_code'=> 200, 'url'=>NULL, 'message' => trans($response)], 200);
            }  
        return back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
         if ($request->ajax()) {
                return response()->json(['error' => trans($response)], 404);
                    
            }  
        return back()->withErrors(
            ['email' => trans($response)]
        );
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
