<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('skill_category_id');
            $table->tinyInteger('phase')->default(0);
            //0 is registered
            // 1 is phase 1
            // 2 is phase 2
            // 3 is phase 3
            // 4 is phase 4
            // 5 is certificated
            $table->tinyInteger('status')->default(-1);


            $table->unsignedBigInteger('expert_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expert_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('skill_category_id')->references('id')->on('skill_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
}
