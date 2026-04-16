<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateAntrian implements ShouldBroadcast
{
    public $jadwalId;
    public $noAntrian;

    public function __construct($jadwalId, $noAntrian)
    {
        $this->jadwalId = $jadwalId;
        $this->noAntrian = $noAntrian;
    }

    public function broadcastOn()
    {
        return new Channel('antrian');
    }
}
