<?php

namespace Database\Seeders;

use App\Models\EventStage;
use Illuminate\Database\Seeder;

class EventStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        EventStage::factory(10)->create();
    }
}
