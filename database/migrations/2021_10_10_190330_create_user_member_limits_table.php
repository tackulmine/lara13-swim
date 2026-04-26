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
        Schema::create('user_member_limits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('master_gaya_id');
            $table->integer('periode_week')->default(1);
            $table->integer('periode_month')->nullable();
            $table->integer('periode_year')->nullable();
            $table->integer('point')->nullable();
            $table->string('point_text')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('user_member_limits', function (Blueprint $table) {
            $table->unique([
                'user_id',
                'master_gaya_id',
                'periode_week',
                'periode_month',
                'periode_year',
            ], 'user_member_limits_user_gaya_periode_unique');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('master_gaya_id')
                ->references('id')->on('master_gaya')
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
        Schema::table('user_member_limits', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['master_gaya_id']);
            $table->dropForeign(['user_id']);
            $table->dropUnique('user_member_limits_user_gaya_periode_unique');
        });

        Schema::dropIfExists('user_member_limits');
    }
};
