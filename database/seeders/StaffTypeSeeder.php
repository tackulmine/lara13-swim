<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        $multipleUserDatas = [
            [
                'username' => 'admin',
                'email' => 'admin@domain.com',
            ], [
                'username' => 'kiki',
                'email' => 'kiki@domain.com',
            ], [
                'username' => 'jury',
                'email' => 'jury@domain.com',
            ],
        ];

        $multipleUserTypes = [
            ['master_user_type_id' => 1],
            ['master_user_type_id' => 1],
            ['master_user_type_id' => 1],
        ];

        foreach ($multipleUserDatas as $key => $userData) {
            User::updateOrCreate(
                $userData,
                $multipleUserTypes[$key],
            );
        }
    }
}
