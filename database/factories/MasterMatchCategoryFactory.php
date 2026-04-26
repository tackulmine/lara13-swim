<?php

namespace Database\Factories;

use App\Models\MasterMatchCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MasterMatchCategoryFactory extends Factory
{
    protected $model = MasterMatchCategory::class;

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
