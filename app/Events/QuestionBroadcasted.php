<?php

namespace App\Events;

use App\Models\MultiplayerSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionBroadcasted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public MultiplayerSession $session,
        public array $question,
        public $questionAt)
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
            new PrivateChannel("quiz.{$this->session->id}")
        ];
    }


    public function broadcastWith()
    {
        return [
            'question' => $this->question,
            'questionAt' => $this->questionAt->toDateTimeString()
        ];
    }
}