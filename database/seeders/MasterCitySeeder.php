<?php

namespace Database\Seeders;

use App\Libraries\CsvtoArray;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminId = User::find(1)->id;
        $now = Carbon::now();
        $Csv = new CsvtoArray;
        $file = resource_path('csv/cities.csv');
        $header = ['code', 'province_code', 'name', 'lat', 'long'];
        $data = $Csv->csv_to_array($file, $header);
        $data = array_map(function ($arr) use ($now, $adminId) {
            $arr['meta'] = json_encode(['lat' => $arr['lat'], 'long' => $arr['long']]);
            unset($arr['lat'], $arr['long']);

            return $arr + ['created_at' => $now, 'updated_at' => $now, 'created_by' => $adminId];
        }, $data);

        $collection = collect($data);
        foreach ($collection->chunk(50) as $chunk) {
            DB::table('master_cities')->insertOrIgnore($chunk->toArray());
        }
    }
}
