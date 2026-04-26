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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('birth_certificate')->nullable()->after('photo');
            $table->string('family_card')->nullable()->after('birth_certificate');
            $table->string('kta_card')->nullable()->after('family_card');
            $table->string('signature')->nullable()->after('kta_card');
            $table->string('last_education')->nullable()->after('relegion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['birth_certificate', 'family_card', 'kta_card', 'signature', 'last_education']);
        });
    }
};
