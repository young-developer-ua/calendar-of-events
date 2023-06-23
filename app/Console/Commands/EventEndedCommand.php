<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\Candidate\EventEndedNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EventEndedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:event-ended';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Event has ended';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Event $events */
        $events = Event::query()->where('end_date', Carbon::now())->get();
        foreach ($events as $event) {
            $event->user->notify(
                new EventEndedNotification($event->title)
            );
        }

        $this->info('Invitation is sent...');

        return 0;
    }
}
