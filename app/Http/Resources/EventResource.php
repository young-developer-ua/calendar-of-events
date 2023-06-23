<?php

namespace App\Http\Resources;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'color' => $this->color,
            'start' => Carbon::make($this->start_date)->format('Y-m-d\Th:m:i'),
            'end' => $this->end_date ? Carbon::make($this->end_date)->format('Y-m-d\Th:m:i') : null,
            'recurrenceType' => $this->recurrence_type,
            'recurrenceValue' => $this->recurrence_value,
            'daysOfWeek' => $this->recurrence_type == Event::TYPE_WEEKLY ? mb_split(',', $this->recurrence_value) : null,
            'isReminder' => $this->is_reminder,
            'isDone' => $this->is_done,
            'editable' => $this->recurrence_type == Event::TYPE_WEEKLY || $this->recurrence_type == Event::TYPE_MONTHLY || $this->recurrence_type == Event::TYPE_YEARLY ? false : true
        ];
    }
}
