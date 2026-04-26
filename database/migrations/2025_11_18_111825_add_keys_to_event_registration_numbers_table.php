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
        Schema::table('event_registration_numbers', function (Blueprint $table) {
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('master_match_type_id')
                ->references('id')->on('master_match_types')
                ->onDelete('cascade');

            $table->foreign('master_match_category_id')
                ->references('id')->on('master_match_categories')
                ->onDelete('cascade');

            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_registration_numbers', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['master_match_type_id']);
            $table->dropForeign(['master_match_category_id']);
            $table->dropIndex(['order_number']);
        });
    }
};
