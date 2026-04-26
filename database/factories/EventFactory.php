<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $name = $this->faker->name;
        $dt = Carbon::create(date('Y'), date('n'), date('d'), 0);

        return [
            'name' => $name.' Competition',
            'slug' => Str::slug($name),
            'address' => $this->faker->address,
            'location' => $this->faker->city.', '.$this->faker->state,
            // 'description' => '',
            'start_date' => $dt->addDays(1),
            'end_date' => $dt->addDays(1),
            'completed' => false,
            'created_by' => 2,
        ];
    }
}
