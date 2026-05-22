<?php

namespace App\Events;

use App\Models\MatchStat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchStatsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public bool $afterCommit = true;

    public $matchStat;

    /**
     * Create a new event instance.
     */
    public function __construct(MatchStat $matchStat)
    {
        $this->matchStat = $matchStat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $userId = $this->matchStat->tournamentMatch->tournament->user_id;
        
        return [
            new Channel('active-match.' . $this->matchStat->tournament_match_id),
            new Channel('user-screens.' . $userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MatchStatsUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $this->matchStat->load(['tournamentTeam', 'players']);
        return [
            'matchStat' => $this->matchStat->toArray(),
        ];
    }
}
