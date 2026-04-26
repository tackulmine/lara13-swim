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
        Schema::create('master_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('master_school_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_participants');
    }
};
