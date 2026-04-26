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
        Schema::table('user_member', function (Blueprint $table) {
            $table->unsignedTinyInteger('master_member_class_id')
                ->nullable()
                ->after('master_member_type_id');

            $table->foreign('master_member_class_id')
                ->references('id')->on('master_member_classes')
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
        Schema::table('user_member', function (Blueprint $table) {
            $table->dropForeign(['master_member_class_id']);
            $table->dropColumn(['master_member_class_id']);
        });
    }
};
