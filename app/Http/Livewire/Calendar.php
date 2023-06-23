<?php

namespace App\Http\Livewire;

use App\Http\Resources\EventResource;
use App\Repositories\EventRepository;
use Database\Factories\EventFactory;
use Livewire\Component;

class Calendar extends Component
{
    public function render(EventRepository $eventRepository)
    {
//        $i = 0;
//        while ($i < 25) {
//            EventFactory::new()->create(['user_id' => 42]);
//            $i++;
//        }
        $data = [
            'events' => EventResource::collection(
                $eventRepository->getByCurrentUser()
            )
        ];

        return view('livewire.calendar', $data);
    }
}
