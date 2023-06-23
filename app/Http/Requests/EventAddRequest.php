<?php

namespace App\Http\Requests;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * EventAddRequest
 *
 * @property string $title
 * @property string $color
 * @property boolean $isReminder
 * @property Carbon $startDate
 * @property Carbon $endDate
 * @property string $recurrenceType
 * @property string $recurrenceValue
 * @property boolean $isDone
 */
class EventAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'color' => [
                'required',
                'string',
                'regex:/^(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))$/i',
            ],
            'isReminder' => ['required', 'boolean'],
            'startDate' => ['required', 'date'],
            'endDate' => [Rule::requiredIf($this->isReminder == false), 'date', 'after_or_equal:startDate'],
            'recurrenceType' => [Rule::requiredIf($this->isReminder == true), Rule::in(Event::TYPES), 'nullable'],
            'recurrenceValue' => [Rule::requiredIf($this->isReminder  == true && $this->recurrenceType != Event::TYPE_NONE), 'nullable', 'string'],
            'isDone' => ['required', 'boolean']
        ];
    }
}
