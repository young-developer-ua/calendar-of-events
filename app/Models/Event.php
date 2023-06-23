<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Event
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $color
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property string $recurrence_type
 * @property string $recurrence_value
 * @property boolean $is_reminder
 * @property boolean $is_done
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User $user
 */
class Event extends Model
{
    protected $table = 'calendar_events';

    use HasFactory,
        Notifiable;

    const TYPE_NONE = 'none';
    const TYPE_WEEKLY = 'weekly';
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';

    const TYPES = [
        self::TYPE_NONE,
        self::TYPE_WEEKLY,
        self::TYPE_MONTHLY,
        self::TYPE_YEARLY
    ];

    protected $fillable = [
        'user_id',
        'title',
        'color',
        'start_date',
        'end_date',
        'recurrence_type',
        'recurrence_value',
        'is_reminder',
        'is_done'
    ];

    public static $snakeAttributes = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
