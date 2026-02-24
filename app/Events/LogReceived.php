<?php

namespace App\Events;

use App\Models\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Log $log)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('logs.' . $this->log->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'log.received';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->log->id,
            'level' => $this->log->level,
            'message' => $this->log->message,
            'context' => $this->log->context,
            'created_at' => $this->log->created_at,
            'teste' => 'Um novo log foi recebido',
        ];
    }
}
