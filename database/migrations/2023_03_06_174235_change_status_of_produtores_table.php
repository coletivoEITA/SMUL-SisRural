<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProdutorUnidadeProdutivaStatusEnum;


class ChangeStatusOfProdutoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtores', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtores', function (Blueprint $table) {
            $table->enum('status', ProdutorUnidadeProdutivaStatusEnum::getValues())->default(ProdutorUnidadeProdutivaStatusEnum::Ativo);
        });
    }
}
