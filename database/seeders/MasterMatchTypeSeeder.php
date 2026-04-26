<?php

namespace Database\Seeders;

use App\Models\MasterMatchType;
use Illuminate\Database\Seeder;

class MasterMatchTypeSeeder extends Seeder
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
                'name' => '25 M Gaya Bebas PA',
            ], [
                'name' => '25 M Gaya Bebas PI',
            ], [
                'name' => '25 M Gaya Dada PA',
            ], [
                'name' => '25 M Gaya Dada PI',
            ], [
                'name' => '25 M Gaya Punggung PA',
            ], [
                'name' => '25 M Gaya Punggung PI',
            ], [
                'name' => '25 M Gaya Kupu-kupu PA',
            ], [
                'name' => '25 M Gaya Kupu-kupu PI',
            ], [
                'name' => '50 M Gaya Bebas PA',
            ], [
                'name' => '50 M Gaya Bebas PI',
            ], [
                'name' => '50 M Gaya Dada PA',
            ], [
                'name' => '50 M Gaya Dada PI',
            ], [
                'name' => '50 M Gaya Punggung PA',
            ], [
                'name' => '50 M Gaya Punggung PI',
            ], [
                'name' => '50 M Gaya Kupu-kupu PA',
            ], [
                'name' => '50 M Gaya Kupu-kupu PI',
            ], [
                'name' => '100 M Gaya Bebas PA',
            ], [
                'name' => '100 M Gaya Bebas PI',
            ], [
                'name' => '100 M Gaya Dada PA',
            ], [
                'name' => '100 M Gaya Dada PI',
            ], [
                'name' => '100 M Gaya Punggung PA',
            ], [
                'name' => '100 M Gaya Punggung PI',
            ], [
                'name' => '100 M Gaya Kupu-kupu PA',
            ], [
                'name' => '100 M Gaya Kupu-kupu PI',
            ],
        ];

        foreach ($data as $values) {
            $values = array_merge($values, ['created_by' => 2]);
            MasterMatchType::create($values);
        }
        // MasterMatchType::factory(10)->create();
    }
}
