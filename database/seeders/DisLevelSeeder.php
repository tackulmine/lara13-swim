<?php

namespace Database\Seeders;

use App\Models\EventSessionParticipant;
use Illuminate\Database\Seeder;

class DisLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventSessionParticipants = EventSessionParticipant::where('disqualification', true)->get();

        foreach ($eventSessionParticipants as $eventSessionParticipant) {
            $eventSessionParticipant->update(['dis_level' => 2]);
        }
    }
}
