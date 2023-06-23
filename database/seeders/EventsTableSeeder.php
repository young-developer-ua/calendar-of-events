<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $users */
        $users = User::query()->get();
        foreach ($users as $user) {
            Event::factory()->for($user)->count(5)->create();
        }
    }
}
