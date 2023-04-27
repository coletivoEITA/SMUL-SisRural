<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SituacaoInfraFerramentaEnum;

class ChangeSituacaoOfUnidadeProdutivaInfraFerramentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidade_produtiva_infra_ferramentas', function (Blueprint $table) {
            $table->text('situacao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidade_produtiva_infra_ferramentas', function (Blueprint $table) {
            $table->enum("situacao", SituacaoInfraFerramentaEnum::getValues())->nullable()->default(null);
        });
    }
}
