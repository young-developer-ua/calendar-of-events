<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * EventUpdateRequest
 *
 * @property boolean $isReminder
 * @property string $title
 * @property string $color
 * @property int $startDate
 * @property int $endDate
 * @property string $recurrenceType
 * @property string $recurrenceValue
 * @property boolean $isDone
 */
class EventUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'eventId' => ['required', Rule::exists(Event::class, 'id')],
            'title' => ['string', 'max:255'],
            'isReminder' => ['required', 'boolean'],
            'color' => [
                'string',
                'regex:/^(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))$/i',
            ],
            'startDate' => ['date'],
            'endDate' => [Rule::requiredIf($this->isReminder == false), 'date', 'after_or_equal:startDate'],
            'recurrenceType' => [Rule::requiredIf($this->isReminder == true), 'nullable', Rule::in(Event::TYPES)],
            'recurrenceValue' => [Rule::requiredIf($this->isReminder  == true && $this->recurrenceType != Event::TYPE_NONE), 'nullable', 'string'],
            'isDone' => ['required', 'boolean']
        ];
    }
}
