<?php

namespace Database\Seeders;

use App\Models\MasterSchool;
use Illuminate\Database\Seeder;

class MasterSchoolSeeder extends Seeder
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
                'name' => 'TK 1',
            ], [
                'name' => 'TK 2',
            ], [
                'name' => 'SDN 1',
            ], [
                'name' => 'SDN 2',
            ], [
                'name' => 'SDN 3',
            ], [
                'name' => 'SDN 4',
            ], [
                'name' => 'SDN 5',
            ], [
                'name' => 'SMP 1',
            ], [
                'name' => 'SMP 2',
            ], [
                'name' => 'SMP 3',
            ],
        ];

        foreach ($data as $values) {
            $values = array_merge($values, ['created_by' => 2]);
            MasterSchool::create($values);
        }

        // MasterSchool::factory(10)->create();
    }
}
