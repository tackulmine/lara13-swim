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
            $table->boolean('is_no_point')->default(true)->index();
            $table->integer('point')->nullable();
            $table->string('point_text')->nullable();
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
            $table->dropIndex(['is_no_point']);

            $table->dropColumn('is_no_point');
            $table->dropColumn('point');
            $table->dropColumn('point_text');
        });
    }
};
