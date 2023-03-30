<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResiduoOrganicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residuo_organicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('unidade_produtiva_residuo_organicos', function (Blueprint $table) {
            $table->string('id')->index();
            $table->unsignedBigInteger('uid')->autoIncrement();

            $table->string('unidade_produtiva_id');
            $table->foreign('unidade_produtiva_id', 'c_unid_prod_res_org_u_p_i')->references('id')->on('unidade_produtivas')->onDelete('cascade');

            $table->unsignedBigInteger('residuo_organico_id');
            $table->foreign('residuo_organico_id', 'c_unid_prod_res_org_id')->references('id')->on('residuo_organicos')->onDelete('cascade');

            $table->boolean('app_sync')->nullable();

            $table->unique(['unidade_produtiva_id', 'residuo_organico_id'], 'uniq_unid_prod_res_org_id');

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
        Schema::dropIfExists('residuo_organicos');
        Schema::dropIfExists('unidade_produtiva_residuo_organicos');
    }
}
