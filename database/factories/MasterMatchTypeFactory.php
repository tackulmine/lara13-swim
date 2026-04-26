<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MasterMatchTypeFactory extends Factory
{
    protected $model = MasterMatchType::class;

    public function definition()
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'created_by' => 2,
        ];
    }
}
