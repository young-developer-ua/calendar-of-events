<?php

namespace Database\Factories;

use Carbon\Carbon;
use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'color' => fake()->hexColor,
            'start_date' => Carbon::tomorrow()->format('Y-m-d h:m:i'),
            'end_date' => Carbon::tomorrow()->format('Y-m-d h:m:i'),
        ];
    }
}
