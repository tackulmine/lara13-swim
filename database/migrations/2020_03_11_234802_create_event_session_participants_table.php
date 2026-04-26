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
        Schema::create('event_session_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_session_id');
            $table->unsignedBigInteger('master_participant_id');
            $table->integer('track');
            $table->integer('point')->nullable();
            $table->string('point_text')->nullable();
            $table->boolean('disqualification')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_session_participants');
    }
};
