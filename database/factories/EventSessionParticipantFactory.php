<?php

namespace Database\Factories;

use App\Models\EventSession;
use App\Models\EventSessionParticipant;
use App\Models\MasterParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventSessionParticipantFactory extends Factory
{
    protected $model = EventSessionParticipant::class;

    public function definition()
    {
        // $track = $this->faker->randomDigitNotNull;
        $track = rand(1, 5);
        // $hour = $this->faker->numberBetween(0,23);
        $hour = 0;
        $minute = $this->faker->numberBetween(0, 2);
        $second = $this->faker->numberBetween(0, 59);
        $milisecond = $this->faker->numberBetween(0, 99);
        // $point_text = str_pad($hour, 2, "0", STR_PAD_LEFT) . ':' . str_pad($minute, 2, "0", STR_PAD_LEFT) . ':' . str_pad($second, 2, "0", STR_PAD_LEFT) . '.' . str_pad($milisecond, 2, "0", STR_PAD_LEFT);
        $point_text = str_pad($minute, 2, '0', STR_PAD_LEFT).':'.str_pad($second, 2, '0', STR_PAD_LEFT).'.'.str_pad($milisecond, 2, '0', STR_PAD_LEFT);

        return [
            'event_session_id' => EventSession::orderByRaw('RAND()')->first()->id,
            'master_participant_id' => MasterParticipant::orderByRaw('RAND()')->first()->id,
            'track' => $track,
            // 'point' => str_replace([':', '.'], '', $point_text),
            // 'point_text' => $point_text,
            'disqualification' => false,
            'notes' => '',
            'created_by' => 2,
        ];
    }
}
