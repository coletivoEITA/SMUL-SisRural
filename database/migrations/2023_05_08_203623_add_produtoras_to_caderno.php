<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProdutorasToCaderno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('caderno_model_produtor_model', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('caderno_model_id');
          $table->foreign('caderno_model_id')->references('id')
          ->on('cadernos')->onDelete('cascade');
          $table->string('produtor_model_id');
          $table->foreign('produtor_model_id')->references('id')
          ->on('produtores')->onDelete('cascade');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caderno_model_produtor_model');
    }
}
