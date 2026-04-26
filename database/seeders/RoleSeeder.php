<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Superuser',
                'slug' => 'superuser',
            ], [
                'name' => 'Coach',
                'slug' => 'coach',
            ], [
                'name' => 'Jury',
                'slug' => 'jury',
            ], [
                'name' => 'Member',
                'slug' => 'member',
            ], [
                'name' => 'External',
                'slug' => 'external',
            ],
        ];
        foreach ($data as $roles) {
            Role::updateOrCreate($roles, $roles);
        }
    }
}
