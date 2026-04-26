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
        Schema::table('events', function (Blueprint $table) {
            $table->integer('reg_style_per_price')->nullable()->after('reg_style_min');
            $table->integer('reg_style_max_price')->nullable()->after('reg_style_per_price');
            $table->integer('reg_relay_per_price')->nullable()->after('reg_style_max_price');
            $table->integer('reg_style_max_price_count')->nullable()->after('reg_relay_per_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('reg_style_per_price');
            $table->dropColumn('reg_style_max_price');
            $table->dropColumn('reg_relay_per_price');
            $table->dropColumn('reg_style_max_price_count');
        });
    }
};
