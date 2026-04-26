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
        Schema::table('event_sessions', function (Blueprint $table) {
            $table->index('completed');
            $table->foreign('event_stage_id')
                ->references('id')->on('event_stages')
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
        Schema::table('event_sessions', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['event_stage_id']);
            $table->dropIndex(['completed']);
        });
    }
};
