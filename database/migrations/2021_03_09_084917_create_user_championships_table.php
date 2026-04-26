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
        Schema::create('user_championships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('master_championship_id');
            $table->integer('periode_month')->nullable();
            $table->integer('periode_year')->nullable();
            $table->integer('point')->nullable();
            $table->string('point_text')->nullable();
            $table->text('rank')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('user_championships', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('master_championship_id')
                ->references('id')->on('master_championships')
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
        Schema::table('user_championships', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['master_championship_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('user_championships');
    }
};
