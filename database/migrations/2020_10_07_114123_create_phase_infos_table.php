<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhaseInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //페이즈 인포는 끊임없이 쌓이는 데이터야.  해당유저에 대해 전문가가 피드백을 줄때마다 생성되는걸로
        Schema::create('phase_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('expert_id');
            $table->unsignedBigInteger('skill_category_id');
            $table->tinyInteger('phase');
            $table->tinyInteger('status');
            //0->pending
            //1->Not approved
            //2->Approved
            $table->text('details');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('expert_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('skill_category_id')->references('id')->on('skill_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phase_infos');
    }
}
