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
        Schema::table('master_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('master_school_id')->nullable()->change();
            $table->dropForeign(['master_school_id']);
            $table->foreign('master_school_id')
                ->references('id')->on('master_schools')
                ->onDelete('set null');

            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->dropForeign(['updated_by']);
            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_participants', function (Blueprint $table) {
            $table->dropForeign(['master_school_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
        Schema::table('master_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('master_school_id')->nullable(false)->change();
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
        });
        Schema::table('master_participants', function (Blueprint $table) {
            $table->foreign('master_school_id')
                ->references('id')->on('master_schools')
                ->onDelete('cascade');
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('updated_by')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }
};
