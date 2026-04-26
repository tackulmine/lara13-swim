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
        Schema::table('event_session_participants', function (Blueprint $table) {
            // 1 = SP, 2 = DQ, 3 = NS
            $table->smallInteger('dis_level')->nullable()->after('disqualification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_session_participants', function (Blueprint $table) {
            $table->dropColumn('dis_level');
        });
    }
};
