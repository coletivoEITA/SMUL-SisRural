<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinacaoProducaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinacao_producao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('unidade_produtiva_destinacao_producao', function (Blueprint $table) {
            $table->string('id')->index();
            $table->unsignedBigInteger('uid')->autoIncrement();

            $table->string('unidade_produtiva_id');
            $table->foreign('unidade_produtiva_id', 'c_unid_prod_dest_prod_u_p_i')->references('id')->on('unidade_produtivas')->onDelete('cascade');

            $table->unsignedBigInteger('destinacao_producao_id');
            $table->foreign('destinacao_producao_id', 'c_unid_prod_dest_prod_id')->references('id')->on('destinacao_producao')->onDelete('cascade');

            $table->boolean('app_sync')->nullable();

            $table->unique(['unidade_produtiva_id', 'destinacao_producao_id'], 'unid_can_com');

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
        Schema::dropIfExists('destinacao_producao');
        Schema::dropIfExists('unidade_produtiva_destinacao_producao');
    }
}
