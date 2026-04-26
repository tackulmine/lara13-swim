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
        Schema::create('external_athlete_best_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('external_swimming_style_id');
            $table->unsignedBigInteger('external_swimming_athlete_id');
            $table->unsignedBigInteger('external_swimming_event_id');
            $table->char('year', 4);
            $table->integer('point');
            $table->string('point_text');
            $table->string('fp')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('external_athlete_best_times', function (Blueprint $table) {
            $table->foreign('external_swimming_style_id')
                ->references('id')->on('external_swimming_styles')
                ->onDelete('cascade');
            $table->foreign('external_swimming_athlete_id')
                ->references('id')->on('external_swimming_athletes')
                ->onDelete('cascade');
            $table->foreign('external_swimming_event_id')
                ->references('id')->on('external_swimming_events')
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
        Schema::table('external_athlete_best_times', function (Blueprint $table) {
            $table->dropForeign(['external_swimming_style_id']);
            $table->dropForeign(['external_swimming_athlete_id']);
            $table->dropForeign(['external_swimming_event_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('external_athlete_best_times');
    }
};
