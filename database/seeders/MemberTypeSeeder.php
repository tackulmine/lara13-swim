<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
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
                'username' => 'member',
                'email' => 'member@domain.com',
            ],
        ];

        $multipleUserTypes = [
            ['master_user_type_id' => 2],
        ];

        foreach ($multipleUserDatas as $key => $userData) {
            User::updateOrCreate(
                $userData,
                $multipleUserTypes[$key],
            );
        }

        $members = User::whereNull('master_user_type_id')->update(['master_user_type_id' => 2]);
    }
}
