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
        Schema::create('user_staff', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('master_staff_type_id');
        });

        Schema::table('user_staff', function (Blueprint $table) {
            $table->primary(['user_id', 'master_staff_type_id']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('master_staff_type_id')
                ->references('id')->on('master_staff_types')
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
        Schema::table('user_staff', function (Blueprint $table) {
            $table->dropForeign(['master_staff_type_id']);
            $table->dropForeign(['user_id']);
            $table->dropPrimary(['user_id', 'master_staff_type_id']);
        });
        Schema::dropIfExists('user_staff');
    }
};
