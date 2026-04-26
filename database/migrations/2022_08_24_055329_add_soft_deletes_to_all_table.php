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
            $table->softDeletes();
        });
        Schema::table('master_schools', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_match_types', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_match_categories', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_participants', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_championships', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_gaya', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
        Schema::table('master_member_classes', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable()->after('updated_by');
            $table->foreign('deleted_by')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
        });
        Schema::table('master_schools', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_match_types', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_match_categories', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_participants', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_championships', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_gaya', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
        Schema::table('master_member_classes', function (Blueprint $table) {
            $table->dropColumn(['deleted_at']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted_by']);
        });
    }
};
