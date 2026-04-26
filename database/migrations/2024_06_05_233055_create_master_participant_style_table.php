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
        Schema::create('master_participant_style', function (Blueprint $table) {
            $table->unsignedBigInteger('master_participant_id');
            $table->unsignedTinyInteger('master_match_type_id');
            $table->boolean('is_no_point')->default(true)->index();
            $table->integer('point')->nullable();
            $table->string('point_text')->nullable();
        });

        Schema::table('master_participant_style', function (Blueprint $table) {
            $table->foreign('master_participant_id')
                ->references('id')->on('master_participants')
                ->onDelete('cascade');

            $table->foreign('master_match_type_id')
                ->references('id')->on('master_match_types')
                ->onDelete('cascade');

            $table->unique(['master_participant_id', 'master_match_type_id'], 'master_participant_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_participant_style', function (Blueprint $table) {
            $table->dropForeign(['master_participant_id']);
            $table->dropForeign(['master_match_type_id']);

            // $table->dropUnique(['event_registration_id', 'master_match_type_id']);
            $table->dropUnique('master_participant_type_unique');
        });

        Schema::dropIfExists('master_participant_style');
    }
};
