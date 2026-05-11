<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActiveMatchVisibilityToggled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $isVisible;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $isVisible)
    {
        $this->userId = $userId;
        $this->isVisible = $isVisible;
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
        return 'ActiveMatchVisibilityToggled';
    }
}
