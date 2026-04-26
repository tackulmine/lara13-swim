<?php

namespace Database\Seeders;

use App\Models\EventSession;
use Illuminate\Database\Seeder;

class EventSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        EventSession::factory(20)->create();
    }
}
