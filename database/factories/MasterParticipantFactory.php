<?php

namespace Database\Factories;

use App\Models\MasterParticipant;
use App\Models\MasterSchool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MasterParticipantFactory extends Factory
{
    protected $model = MasterParticipant::class;

    public function definition()
    {
        $name = $this->faker->name;
        $address = $this->faker->address;
        $addresses = array_filter(array_map('trim', explode(',', $address)));
        $cityArr = explode(' ', $addresses[1]);
        array_pop($cityArr);
        $city = implode(' ', $cityArr);

        return [
            'master_school_id' => MasterSchool::orderByRaw('RAND()')->first()->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => $address,
            'location' => $city.(isset($addresses[2]) ? ', '.$addresses[2] : ''),
            'birth_date' => $this->faker->dateTimeInInterval('-30 years', '-5 years'),
            'created_by' => 2,
        ];
    }
}
