<?php

namespace Tests\Feature;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    public function testGetCalendarList()
    {
        $password = fake()->password;
        /** @var User $user */
        $user = $this->createUser($password);

        $events = Event::factory()->count(10)->for($user)->create(['is_done' => rand(0, 1)]);
        $eventsResource = EventResource::collection($events)->toArray(request());
        $this->actingAs($user)
            ->get('events')
            ->assertOk()
            ->assertJson($eventsResource);
    }
}
