<?php

namespace App\Listeners;

use App\Events\AppInsightsLogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppInsightsLogEventListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(AppInsightsLogEvent $event)
    {
        $raw = unserialize($event->obj);
        $raw->enableHttpClient();
        $raw->send();
    }
}
