<?php

namespace App\Providers\App\Listeners;

use App\Events\SendWelcomeMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendWelcomeMail  $event
     * @return void
     */
    public function handle(SendWelcomeMail $event)
    {
        //
    }
}
