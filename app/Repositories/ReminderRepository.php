<?php

namespace App\Repositories;

use App\Exceptions\ErrorsException;
use App\Http\Requests\ReminderAddRequest;
use App\Http\Requests\ReminderUpdateRequest;
use App\Models\Reminder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReminderRepository
{
    public function getByCurrentUser(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Reminder::query()
            ->whereBelongsTo(auth()->user())
            ->get();
    }

    public function store(ReminderAddRequest $request): Reminder
    {
        try {
            $reminder = new Reminder();
            $reminder->title = $request->title;
            $reminder->color = $request->color;
            $reminder->date = $request->date;
            $reminder->regularity = $request->regularity;
            $reminder->user()->associate(auth()->user());
            $reminder->save();
            return $reminder;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Creating error');
        }
    }

    public function update(ReminderUpdateRequest $request, Reminder $reminder): ?Reminder
    {
        try {
            DB::beginTransaction();
            $reminder->title = $request->title;
            $reminder->color = $request->color;
            $reminder->date = $request->date;
            $reminder->regularity = $request->regularity;
            $reminder->save();
            DB::commit();
            return $reminder;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Updating error');
        }
    }

    public function destroy(Reminder $reminder): void
    {
        try {
            DB::beginTransaction();
            $reminder->delete();
            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ErrorsException('Deleting error');
        }
    }
}
