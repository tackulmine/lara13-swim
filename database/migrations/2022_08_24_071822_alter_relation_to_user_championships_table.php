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
        Schema::table('user_championships', function (Blueprint $table) {
            $table->dropForeign(['master_championship_id']);
            $table->dropColumn(['master_championship_id']);

            $table->dropColumn(['periode_month']);
            $table->dropColumn(['periode_year']);
        });

        Schema::table('user_championships', function (Blueprint $table) {
            $table->unsignedBigInteger('championship_event_id')
                ->after('user_id');
            $table->foreign('championship_event_id')
                ->references('id')->on('championship_events')
                ->onDelete('cascade');

            $table->unsignedBigInteger('master_championship_gaya_id')
                ->after('championship_event_id');
            $table->foreign('master_championship_gaya_id')
                ->references('id')->on('master_championship_gaya')
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
        Schema::table('user_championships', function (Blueprint $table) {
            $table->unsignedBigInteger('master_championship_id')->after('user_id');
            $table->integer('periode_month')->nullable()->after('master_championship_id');
            $table->integer('periode_year')->nullable()->after('periode_month');

            $table->foreign('master_championship_id')
                ->references('id')->on('master_championships')
                ->onDelete('cascade');
        });

        Schema::table('user_championships', function (Blueprint $table) {
            $table->dropForeign(['championship_event_id']);
            $table->dropColumn(['championship_event_id']);
            $table->dropForeign(['master_championship_gaya_id']);
            $table->dropColumn(['master_championship_gaya_id']);
        });
    }
};
