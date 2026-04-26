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
        Schema::table('event_category', function (Blueprint $table) {
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('master_match_category_id')
                ->references('id')->on('master_match_categories')
                ->onDelete('cascade');

            $table->index(['ordering']);
            $table->primary(['event_id', 'master_match_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_category', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropIndex(['ordering']);
            $table->dropForeign(['master_match_category_id']);
            $table->dropPrimary(['event_id', 'master_match_category_id']);
        });
    }
};
