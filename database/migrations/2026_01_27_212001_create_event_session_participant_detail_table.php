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
        Schema::create('event_session_participant_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('event_session_participant_id');
            $table->unsignedBigInteger('master_participant_id');
            $table->tinyInteger('ordering')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_session_participant_detail');
    }
};
