<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaDisponivelExpansao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidade_produtivas', function (Blueprint $table) {
            $table->decimal('area_disponivel_expansao', 8, 2)->nullable();
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
            $table->dropColumn('area_disponivel_expansao');
        });
    }
}
