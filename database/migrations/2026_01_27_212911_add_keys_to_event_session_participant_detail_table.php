<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_session_participant_detail', function (Blueprint $table) {
            $table->foreign('event_session_participant_id', 'espd_event_session_participant_id_fk')
                ->references('id')->on('event_session_participants')
                ->onDelete('cascade');

            $table->foreign('master_participant_id', 'espd_master_participant_id_fk')
                ->references('id')->on('master_participants')
                ->onDelete('cascade');

            $table->index('ordering', 'espd_ordering_idx');
            $table->primary(['event_session_participant_id', 'master_participant_id'], 'event_session_participant_detail_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_session_participant_detail', function (Blueprint $table) {
            $table->dropForeign('espd_event_session_participant_id_fk');
            $table->dropForeign('espd_master_participant_id_fk');
            $table->dropIndex('espd_ordering_idx');
            $table->dropPrimary('event_session_participant_detail_pk');
        });
    }
};
