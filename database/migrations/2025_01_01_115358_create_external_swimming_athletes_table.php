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
        Schema::create('external_swimming_athletes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('nisnas')->nullable();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'mix'])->default('male');
            $table->string('city_code')->nullable();
            $table->unsignedBigInteger('external_swimming_club_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('external_swimming_athletes', function (Blueprint $table) {
            $table->foreign('city_code')
                ->references('code')->on('master_cities')
                ->onDelete('cascade');
            $table->foreign('external_swimming_club_id')
                ->references('id')->on('external_swimming_clubs')
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
        Schema::table('external_swimming_athletes', function (Blueprint $table) {
            $table->dropForeign(['city_code']);
            $table->dropForeign(['external_swimming_club_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::dropIfExists('external_swimming_athletes');
    }
};
