<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimerUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $status;
    public $duration;
    public $remainingSeconds;
    public $endsAt;
    public $visible;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $status, $duration, $remainingSeconds, $endsAt, $visible)
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->duration = $duration;
        $this->remainingSeconds = $remainingSeconds;
        $this->endsAt = $endsAt;
        $this->visible = (bool)$visible;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user-screens.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TimerUpdated';
    }
}
