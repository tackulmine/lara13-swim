<?php

namespace Database\Seeders;

use App\Models\MasterParticipant;
use Illuminate\Database\Seeder;

class MasterParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        MasterParticipant::factory(50)->create();
    }
}
