<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $name = $this->faker->unique()->name;

        return [
            'name' => $name,
            'username' => Str::slug($name),
            // 'username' => $this->faker->unique()->username,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }
}
