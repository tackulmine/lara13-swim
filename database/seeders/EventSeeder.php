<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        $name = 'Angkasa Putra Cup III 2020';
        $dt = Carbon::create(date('Y'), date('n'), date('d'), 0);
        Event::create([
            'name' => $name,
            'slug' => Str::slug($name),
            'address' => 'Angkasa Putra Swimming Pool, Megare',
            'location' => 'Taman, Sidoarjo',
            // 'description' => '',
            'start_date' => $dt->addDays(1),
            'end_date' => $dt->addDays(1),
            'completed' => false,
            'created_by' => 2,
        ]);

        // Event::factory(1)->create();
    }
}
