<?php

namespace Database\Seeders;

use App\Libraries\CsvtoArray;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterProvinceSeeder extends Seeder
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
        $csv = new CsvtoArray;
        $file = resource_path('csv/provinces.csv');
        $header = ['code', 'name', 'lat', 'long'];
        $data = $csv->csv_to_array($file, $header);
        $data = array_map(function ($arr) use ($now, $adminId) {
            $arr['meta'] = json_encode(['lat' => $arr['lat'], 'long' => $arr['long']]);
            unset($arr['lat'], $arr['long']);

            return $arr + ['created_at' => $now, 'updated_at' => $now, 'created_by' => $adminId];
        }, $data);

        DB::table('master_provinces')->insertOrIgnore($data);
    }
}
