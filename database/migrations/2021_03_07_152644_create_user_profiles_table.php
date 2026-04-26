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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('nik')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->enum('relegion', ['islam', 'protestan', 'katolik', 'hindu', 'buddha', 'khonghucu'])->nullable();
            $table->double('height', 8, 2)->nullable();
            $table->double('weight', 8, 2)->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('marital_status')->default('single');
            $table->string('nationality')->default('indonesia');
            $table->string('profession')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('second_phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('telegram_number')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('twitter_id')->nullable();
            $table->string('instagram_id')->nullable();
            $table->string('tiktok_id')->nullable();
            $table->string('ayah')->nullable();
            $table->string('ibu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
