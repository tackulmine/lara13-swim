<?php

namespace Database\Factories;

use App\Models\EventSession;
use App\Models\EventStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventSessionFactory extends Factory
{
    protected $model = EventSession::class;

    public function definition()
    {
        $session = $this->faker->randomDigitNotNull;

        return [
            'event_stage_id' => EventStage::orderByRaw('RAND()')->first()->id,
            'session' => $session,
            'completed' => false,
            'created_by' => 2,
        ];
    }
}
