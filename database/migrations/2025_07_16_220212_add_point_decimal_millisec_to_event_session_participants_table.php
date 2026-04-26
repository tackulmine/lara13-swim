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
            $table->double('point_decimal', 8, 3)->nullable()->after('point_text');
            $table->string('point_text_decimal')->nullable()->after('point_decimal');
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
            $table->dropColumn('point_decimal');
            $table->dropColumn('point_text_decimal');
        });
    }
};
