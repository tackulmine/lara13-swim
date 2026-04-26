<?php

namespace Database\Seeders;

use App\Models\MasterUserType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserMemberRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userMembers = User::with('roles')->where('master_user_type_id', MasterUserType::MEMBER_ID)->get();

        $role = Role::where('slug', 'member')->first();

        foreach ($userMembers as $userMember) {
            $userMember->roles()->sync($role);
        }
    }
}
