<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

class CreateAchievementsTables extends Migration
{
    public $achievement_details;
    public $achievement_progress;

    public function __construct()
    {

        $this->achievement_details = Config::get('achievements.table_names.details');
        $this->achievement_progress = Config::get('achievements.table_names.progress');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->achievement_details, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('points')->default(1);
            $table->boolean('secret')->default(false);
            $table->string('class_name');
            $table->timestamps();
        });
        Schema::create($this->achievement_progress, function (Blueprint $table) {
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
        Schema::dropIfExists($this->achievement_progress);
        Schema::dropIfExists($this->achievement_details);
    }
}
