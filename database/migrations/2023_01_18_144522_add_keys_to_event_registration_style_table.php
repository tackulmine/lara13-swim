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
        Schema::table('event_registration_style', function (Blueprint $table) {
            $table->foreign('event_registration_id')
                ->references('id')->on('event_registrations')
                ->onDelete('cascade');

            $table->foreign('master_match_type_id')
                ->references('id')->on('master_match_types')
                ->onDelete('cascade');

            $table->unique(['event_registration_id', 'master_match_type_id'], 'event_registration_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_registration_style', function (Blueprint $table) {
            $table->dropForeign(['event_registration_id']);
            $table->dropForeign(['master_match_type_id']);

            // $table->dropUnique(['event_registration_id', 'master_match_type_id']);
            $table->dropUnique('event_registration_type_unique');
        });
    }
};
