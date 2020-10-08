<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadPDFSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_pdfs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_category_id');
            $table->string('filename');
            $table->string('original');
            $table->timestamps();

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
        Schema::dropIfExists('upload_pdfs');
    }
}
