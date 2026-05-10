<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamEliminated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $teamName;
    public $teamLogo;
    public $matchId;

    public function __construct($teamName, $teamLogo, $matchId)
    {
        $this->teamName = $teamName;
        $this->teamLogo = $teamLogo;
        $this->matchId = $matchId;
    }

    public function broadcastOn()
    {
        return new Channel('active-match.' . $this->matchId);
    }
    
    public function broadcastAs()
    {
        return 'TeamEliminated';
    }
}
