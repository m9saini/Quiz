<?php

namespace Modules\EmailTemplates\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\EmailTemplates\Entities\EmailTemplate;

class EmailTemplatesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        EmailTemplate::truncate();
        $insert = array(
        array('id' => 1,'slug' => 'activate-user' ,'name' => "Activate your new  LARAVEL5.6 account",'subject' => 'Activate your new  LARAVEL5.6  account','body'=>'<p><strong>Hello [username] ,</strong></p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Thank you for creating a nSupport account. To activate your account and start using nSupport , please confirm your email address by clicking below.</p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href=\"[activationlink]\">[activationlink]</a></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p>nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()),
        array('id' => 2,'slug' => 'create-user' ,'name' => "New User Notification",'subject' => 'New User Notification','body'=>'<p><strong>Hello [username] ,</strong></p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Welcome to Laravel5.6. You can Login with below credentials.</p>\r\n\r\n<p><strong>Username </strong>: [email]</p>\r\n\r\n<p><strong>Password </strong>: [password]</p>\r\n\r\n<p><strong>Login Url</strong> : [loginurl]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p>nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()),
        array('id' => 3,'slug' => 'forgot-password' ,'name' => "Forgot Password",'subject' => 'Recover Your Password','body'=>'<p><strong>Hello [username] ,</strong></p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; You have just requested to reset your password , please click on to the following link to reset your password.</p>\r\n\r\n<p>Your&nbsp; reset password link is: [resetlink]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p>nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()), 
        array('id' => 4,'slug' => 'contact-us-request' ,'name' => "Contact Us",'subject' => 'Contactus Request','body'=>'<p><strong>Hello [username] ,</strong></p>\r\n\r\n<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; You have received new contact us request.</p>\r\n\r\n<p><strong>Name</strong>: [name]</p>\r\n\r\n<p><strong>Email</strong>: [email]</p>\r\n\r\n<p><strong>Message </strong>: [message]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p>nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()),
        array('id' => 5,'slug' => 'report' ,'name' => "Report",'subject' => 'Report request','body'=>'<p><strong>Hello [username] ,</strong></p>\r\n\r\n<p>[name] has reported about [report_about]. Please check the below details.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Report Username</strong>: [name]</p>\r\n\r\n<p><strong>[type] Details</strong>: [link]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p>nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()),
        array('id' => 6,'slug' => 'account-deactivation-email' ,'name' => "Account deactivation email",'subject' => 'Account deactivation email','body'=>'<p>Dear [USERNAME],</p>\r\n\r\n<p>[CONFIRMATIONMSG]</p>\r\n\r\n<p><strong>Resion </strong>: [RESION]</p>\r\n\r\n<p><strong>Deactivate Date</strong> : [DEACTIVATE_DATE]</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Thanks<br />\r\nSupport Team</p>','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()),
        );
        EmailTemplate::insert($insert);
    }
}
