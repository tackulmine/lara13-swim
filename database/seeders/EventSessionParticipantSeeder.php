<?php

namespace Database\Seeders;

use App\Models\EventSessionParticipant;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EventSessionParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @void
     */
    public function run()
    {
        // Schema::disableForeignKeyConstraints();

        // EventSessionParticipant::truncate();

        $participants = EventSessionParticipant::factory(500)->make();
        foreach ($participants as $participant) {
            repeat:
            try {
                if (
                    EventSessionParticipant::where('event_session_id', $participant->event_session_id)
                        // ->where('master_participant_id', $participant->master_participant_id)
                        ->where('track', $participant->track)
                        ->doesntExist()
                ) {
                    $participant->save();
                } else {
                    // $newParticipant = EventSessionParticipant::factory()->make();
                    // $newParticipant->event_session_id = $participant->event_session_id;
                    // $newParticipant->master_participant_id = $participant->master_participant_id;
                    // $participant = $newParticipant;
                    // goto repeat;
                    continue;
                }
            } catch (QueryException $e) {
                $newParticipant = EventSessionParticipant::factory()->make();
                $newParticipant->event_session_id = $participant->event_session_id;
                $newParticipant->master_participant_id = $participant->master_participant_id;
                $participant = $newParticipant;
                goto repeat;
            }
        }

        // Schema::enableForeignKeyConstraints();
    }
}
