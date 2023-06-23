<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\Candidate\EventBeginsNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EventBeginsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:event-begins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Event begins';

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
        $events = Event::query()->where('start_date', Carbon::now())->get();
        foreach ($events as $event) {
            $event->user->notify(
                new EventBeginsNotification($event->title)
            );
        }

        $this->info('Invitation is sent...');

        return 0;
    }
}
