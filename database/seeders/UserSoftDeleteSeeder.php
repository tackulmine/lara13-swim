<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSoftDeleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::onlyTrashed()->get();
        foreach ($users as $user) {
            $user->username = 'archived_'.$user->username;
            $user->email = 'archived_'.$user->email;
            $user->save();
        }
    }
}
