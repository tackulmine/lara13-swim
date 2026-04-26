<?php

namespace Database\Seeders;

use App\Models\MasterStaffType;
use Illuminate\Database\Seeder;

class MasterStaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        MasterStaffType::insert([
            [
                'name' => 'Coach',
                'slug' => 'coach',
            ], [
                'name' => 'Coach Assistant',
                'slug' => 'coach-assistant',
            ],
        ]);
    }
}
