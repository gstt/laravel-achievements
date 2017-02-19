<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievement_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('points')->default(1);
            $table->boolean('secret')->default(false);
            $table->string('class_name');
            $table->timestamps();
        });
        Schema::create('achievement_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('achievement_id');
            $table->morphs('achiever');
            $table->unsignedInteger('points')->default(0);
            $table->timestamp('unlocked_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('achievement_id')->references('id')->on('achievement_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('achievement_progress');
        Schema::dropIfExists('achievement_details');
    }
}
