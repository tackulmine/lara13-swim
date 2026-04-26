<?php

namespace Database\Seeders;

use App\Models\MasterMemberType;
use Illuminate\Database\Seeder;

class MasterMemberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        MasterMemberType::insert([
            [
                'name' => 'Athlete',
                'slug' => 'athlete',
            ], [
                'name' => 'Non Athlete',
                'slug' => 'non-athlete',
            ],
        ]);
    }
}
