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
            $table->boolean('is_reg')->default(false)->after('end_date');
            $table->datetime('reg_end_date')->nullable()->after('is_reg');
            $table->integer('reg_quota')->nullable()->after('reg_end_date');
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
            $table->dropColumn('is_reg');
            $table->dropColumn('reg_end_date');
            $table->dropColumn('reg_quota');
        });
    }
};
