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
        Schema::table('event_stages', function (Blueprint $table) {
            $table->index('completed');
            $table->index('order_number');
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');
            $table->foreign('master_match_type_id')
                ->references('id')->on('master_match_types')
                ->onDelete('cascade');
            $table->foreign('master_match_category_id')
                ->references('id')->on('master_match_categories')
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
        Schema::table('event_stages', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['master_match_category_id']);
            $table->dropForeign(['master_match_type_id']);
            $table->dropForeign(['event_id']);
            $table->dropIndex(['order_number']);
            $table->dropIndex(['completed']);
        });
    }
};
