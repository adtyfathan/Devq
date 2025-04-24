<?php

namespace App\Jobs;

use App\Events\StandingsUpdated;
use App\Models\MultiplayerSession;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OutOfBoundsException;

class BroadcastStandings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public MultiplayerSession $session,
        public $players,
        public $standingsAt,
        public $isLast,
        public $category,
        public $difficulty
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new StandingsUpdated($this->session, $this->players, $this->standingsAt, isLast: $this->isLast, category: $this->category, difficulty: $this->difficulty));
    }
}