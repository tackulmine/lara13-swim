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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('master_user_type_id')
                ->nullable()
                ->after('email_verified_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('master_user_type_id')
                ->references('id')->on('master_user_types')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['master_user_type_id']);
            $table->dropColumn(['master_user_type_id']);
        });
    }
};
