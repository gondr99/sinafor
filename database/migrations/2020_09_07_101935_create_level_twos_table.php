<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelTwosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_twos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('belongs');

            $table->timestamps();

            $table->foreign('belongs')->references('id')->on('level_ones')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_twos');
    }
}
