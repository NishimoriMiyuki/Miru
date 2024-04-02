<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('board_rows', function (Blueprint $table) {
            $table->tinyInteger('difficulty_level_id')->unsigned()->nullable()->change();
            $table->tinyInteger('status_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_rows', function (Blueprint $table) {
            $table->bigInteger('difficulty_level_id')->unsigned()->nullable()->change();
            $table->bigInteger('status_id')->unsigned()->nullable()->change();
        });
    }
};
