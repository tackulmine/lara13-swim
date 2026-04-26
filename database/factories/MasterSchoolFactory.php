<?php

namespace Database\Factories;

use App\Models\MasterSchool;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MasterSchoolFactory extends Factory
{
    protected $model = MasterSchool::class;

    public function definition()
    {
        $name = $this->faker->unique()->company;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'created_by' => 2,
        ];
    }
}
