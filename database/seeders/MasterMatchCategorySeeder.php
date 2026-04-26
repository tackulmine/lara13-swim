<?php

namespace Database\Seeders;

use App\Models\MasterMatchCategory;
use Illuminate\Database\Seeder;

class MasterMatchCategorySeeder extends Seeder
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
                'name' => 'SD 1-2',
            ], [
                'name' => 'SD 3-4',
            ], [
                'name' => 'SD 5-6',
            ], [
                'name' => 'SMP',
            ], [
                'name' => 'SMA',
            ],
        ];

        foreach ($data as $values) {
            $values = array_merge($values, ['created_by' => 2]);
            MasterMatchCategory::create($values);
        }
        // MasterMatchCategory::factory(10)->create();
    }
}
