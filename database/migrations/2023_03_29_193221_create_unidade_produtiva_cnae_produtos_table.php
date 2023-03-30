<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadeProdutivaCnaeProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidade_produtiva_cnae_produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unidade_produtiva_id', 255)->nullable();
            $table->foreign('unidade_produtiva_id')->references('id')
            ->on('unidade_produtivas')->onDelete('set null');
            $table->unsignedBigInteger('cnae_produto_id');
            $table->foreign('cnae_produto_id')->references('id')
            ->on('cnae_produtos')->onDelete('cascade');
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
        Schema::dropIfExists('unidade_produtiva_cnae_produtos');
    }
}
