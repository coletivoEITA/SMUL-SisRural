<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadeProdutivaCulturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidade_produtiva_culturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unidade_produtiva_id', 255)->nullable();
            $table->foreign('unidade_produtiva_id')->references('id')
            ->on('unidade_produtivas')->onDelete('set null');
            $table->unsignedBigInteger('cultura_id');
            $table->foreign('cultura_id')->references('id')
            ->on('culturas')->onDelete('cascade');
            $table->integer('quantidade')->nullable();
            $table->string('observacao', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidade_produtiva_culturas');
    }
}
