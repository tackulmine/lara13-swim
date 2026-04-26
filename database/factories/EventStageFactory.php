<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventStage;
use App\Models\MasterMatchCategory;
use App\Models\MasterMatchType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventStageFactory extends Factory
{
    protected $model = EventStage::class;

    public function definition()
    {
        $stageNumber = $this->faker->unique()->randomNumber(2);
        $number = '1'.str_pad($stageNumber, 2, '0', STR_PAD_LEFT);

        return [
            'event_id' => Event::orderByRaw('RAND()')->first()->id,
            'master_match_type_id' => MasterMatchType::orderByRaw('RAND()')->first()->id,
            'master_match_category_id' => MasterMatchCategory::orderByRaw('RAND()')->first()->id,
            'number' => $number,
            'completed' => false,
            'created_by' => 2,
        ];
    }
}
