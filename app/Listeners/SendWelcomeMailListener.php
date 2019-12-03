<?php

namespace App\Listeners;

use DB,Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Config;
use App\Mail\WelcomeNewUserMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\EmailTemplates\Entities\EmailTemplate;

class SendWelcomeMailListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
         //send the welcome email to the user
        $user = $event->user;
        // DB::table('user_activations')->insert(['id_user'=>$u['id'],'token'=>$u['link']]);
         $verifyUrl =  URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $user->id]
        );
        $emailtemplate = EmailTemplate::where('slug','activate-user')->first();
        $subject = $emailtemplate->subject;
        $body = $emailtemplate->body;
        $body = str_replace('[username]', $user->name, $body);
        $body = str_replace('[activationlink]', $verifyUrl, $body);
        $data1 = [
            'content' => $body,
            'user' => $user,
            'subject' => $subject
        ];
        Mail::send('admin.emails.email-template', $data1, function ($message) use ($data1) {
            $message->to($data1['user']->email)->subject($data1['subject']);
        });    
        //Mail::to($event->user->email)->send(new WelcomeNewUserMail($user));
    }
}
