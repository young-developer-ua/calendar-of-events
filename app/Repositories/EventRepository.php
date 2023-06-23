<?php

namespace App\Repositories;

use App\Exceptions\ErrorsException;
use App\Http\Requests\EventAddRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventRepository
{
    public function getByCurrentUser(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Event::query()
            ->whereBelongsTo(auth()->user())
            ->get();
    }

    public function findOne($id) {
        return Event::query()->findOrFail($id);
    }

    public function store(EventAddRequest $request): ?Event
    {
        try {
            $event = new Event();
            $event->title = $request->title;
            $event->color = $request->color;
            $event->start_date = $request->startDate;
            $event->end_date = $request->endDate;
            $event->is_reminder = $request->isReminder;
            $event->is_done = $request->isDone;
            if ($request->isReminder) {
                $event->recurrence_type = $request->recurrenceType;
                $event->recurrence_value = $request->recurrenceValue;
            }
            $event->user()->associate(auth()->user());
            $event->save();
            return $event;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Creating error');
        }
    }

    public function update(EventUpdateRequest $request, Event $event): ?Event
    {
        try {
            DB::beginTransaction();
            $event->title = $request->title;
            $event->color = $request->color;
            $event->start_date = $request->startDate;
            $event->end_date = $request->endDate;
            $event->is_reminder = $request->isReminder;
            $event->is_done = $request->isDone;
            if ($request->isReminder) {
                $event->recurrence_type = $request->recurrenceType;
                $event->recurrence_value = $request->recurrenceValue;
            }
            $event->save();
            DB::commit();
            return $event;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Updating error');
        }
    }

    public function destroy(Event $event): void
    {
        try {
            DB::beginTransaction();
            $event->delete();
            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Deleting error');
        }
    }

    public function markSwitch(Event $event): ?Event
    {
        try {
            DB::beginTransaction();
            $event->is_done = !$event->is_done;
            $event->save();
            DB::commit();
            return $event;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Updating error');
        }
    }
}
