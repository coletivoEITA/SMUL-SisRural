<?php
use App\Enums\FrequenciaComercializacaoEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposAreaProducao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidade_produtivas', function (Blueprint $table) {
            $table->enum("frequencia_comercializacao", FrequenciaComercializacaoEnum::getValues())->nullable()->default(null);
            $table->boolean('fl_comprova_origem_comercializacao')->nullable();
            $table->text('forma_comprova_comercializacao')->nullable();

            $table->unsignedBigInteger('rendimento_comercializacao_id')->nullable();
            $table->foreign('rendimento_comercializacao_id')->references('id')->on('rendimento_comercializacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidade_produtivas', function (Blueprint $table) {
            $table->dropColumn('frequencia_comercializacao');
            $table->dropColumn('fl_comprova_origem_comercializacao');
            $table->dropColumn('forma_comprova_comercializacao');
            $table->dropForeign('unidade_produtivas_rendimento_comercializacao_id_foreign');
            $table->dropColumn('rendimento_comercializacao_id');
        });
    }
}
