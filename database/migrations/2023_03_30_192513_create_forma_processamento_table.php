<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormaProcessamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forma_processamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('unidade_produtiva_forma_processamento', function (Blueprint $table) {
            $table->string('id')->index();
            $table->unsignedBigInteger('uid')->autoIncrement();

            $table->string('unidade_produtiva_id');
            $table->foreign('unidade_produtiva_id', 'c_unid_prod_for_pro_u_p_i')->references('id')->on('unidade_produtivas')->onDelete('cascade');

            $table->unsignedBigInteger('forma_processamento_id');
            $table->foreign('forma_processamento_id', 'c_unid_prod_for_pro_id')->references('id')->on('forma_processamento')->onDelete('cascade');

            $table->boolean('app_sync')->nullable();

            $table->unique(['unidade_produtiva_id', 'forma_processamento_id'], 'uniq_unid_prod_for_pro_id');

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
        Schema::dropIfExists('forma_processamento');
        Schema::dropIfExists('unidade_produtiva_forma_processamento');
    }
}
