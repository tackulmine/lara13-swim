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
        Schema::create('event_category_type', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id');
            $table->unsignedTinyInteger('master_match_category_id');
            $table->unsignedTinyInteger('master_match_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_category_type');
    }
};
