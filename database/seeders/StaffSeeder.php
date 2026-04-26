<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin Istrator',
                'username' => 'admin',
                'email' => 'admin@domain.com',
                'email_verified_at' => now(),
                'password' => Hash::make('amazing2020'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Kiki',
                'username' => 'kiki',
                'email' => 'kiki@domain.com',
                'email_verified_at' => now(),
                'password' => Hash::make('kiki-centrum'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], [
                'name' => 'Jury',
                'username' => 'jury',
                'email' => 'jury@domain.com',
                'email_verified_at' => now(),
                'password' => Hash::make('jury123'),
                'remember_token' => Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
