<?php

namespace App\Events;

use App\Models\MultiplayerSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StandingsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public MultiplayerSession $session,
        public $players,
        public $standingsAt,
        public $isLast,
        public $category,
        public $difficulty
    ) {}

    public function broadcastOn()
    {
        return [
            new PrivateChannel("quiz.{$this->session->id}")
        ];
    }

    public function broadcastWith()
    {
        return [
            'players' => $this->players,
            'standingsAt' => $this->standingsAt->toDateTimeString(),
            'isLast' => $this->isLast,
            'category' => $this->category,
            'difficulty' => $this->difficulty
        ];
    }
}