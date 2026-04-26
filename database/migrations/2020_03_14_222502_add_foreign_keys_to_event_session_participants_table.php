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
        Schema::table('event_session_participants', function (Blueprint $table) {
            $table->index('disqualification');
            $table->foreign('event_session_id')
                ->references('id')->on('event_sessions')
                ->onDelete('cascade');
            $table->foreign('master_participant_id')
                ->references('id')->on('master_participants')
                ->onDelete('cascade');
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_session_participants', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['master_participant_id']);
            $table->dropForeign(['event_session_id']);
            $table->dropIndex(['disqualification']);
        });
    }
};
