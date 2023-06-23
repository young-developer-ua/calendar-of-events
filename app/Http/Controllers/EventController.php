<?php
namespace App\Http\Controllers;

use App\Facades\PrepareResponse;
use App\Http\Requests\EventAddRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Repositories\EventRepository;
use Illuminate\Http\Response;
use App\Models\Event;

class EventController extends Controller
{
    public function __construct(private readonly EventRepository $eventRepository)
    {
    }

    public function index()
    {
        return  PrepareResponse::getClearResponse(EventResource::collection(
            $this->eventRepository->getByCurrentUser()
        ));
    }

    /**
     * @throws \App\Exceptions\ErrorsException
     */
    public function store(EventAddRequest $request)
    {
        return PrepareResponse::getSuccessResponse(
            EventResource::make(
                $this->eventRepository->store($request)
            ), Response::HTTP_CREATED
        );
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);
        return PrepareResponse::getSuccessResponse(EventResource::make($event));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \App\Exceptions\ErrorsException
     */
    public function update(EventUpdateRequest $request)
    {
        /** @var Event $event */
        $event = $this->eventRepository->findOne($request->eventId);
        $this->authorize('update', $event);
        return PrepareResponse::getSuccessResponse(
            EventResource::make(
                $this->eventRepository->update($request, $event)
            ));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \App\Exceptions\ErrorsException
     */
    public function delete(Event $event)
    {
        $this->authorize('delete', $event);
        $this->eventRepository->destroy($event);
        return PrepareResponse::getDeleteResponse();
    }

    public function render()
    {
        /** @var Event $events */
        $events = Event::select('id','title','start')->get();

        $this->events = json_encode($events);

        return view('livewire.calendar');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \App\Exceptions\ErrorsException
     */
    public function markSwitch(Event $event)
    {
        $this->authorize('update', $event);
        return PrepareResponse::getSuccessResponse(
            EventResource::make(
                $this->eventRepository->markSwitch($event)
            ));
    }
}