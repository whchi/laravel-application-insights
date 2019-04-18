<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Whchi\LaravelApplicationInsights\AppInsights;

class AppInsightsLogEvent
{
    use Dispatchable, InteractsWithSockets;

    public $obj;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AppInsights $obj)
    {
        $this->obj = serialize($obj);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
