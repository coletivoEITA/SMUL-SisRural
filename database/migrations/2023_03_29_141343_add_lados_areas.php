<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLadosAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidade_produtivas', function (Blueprint $table) {
            $table->decimal('area_total_solo_lado1', 8, 2)->nullable();
            $table->decimal('area_total_solo_lado2', 8, 2)->nullable();
            $table->decimal('area_produtiva_lado1', 8, 2)->nullable();
            $table->decimal('area_produtiva_lado2', 8, 2)->nullable();
            $table->decimal('area_disponivel_expansao_lado1', 8, 2)->nullable();
            $table->decimal('area_disponivel_expansao_lado2', 8, 2)->nullable();
            $table->decimal('area_total_solo', 8, 2)->nullable()->change();
            $table->decimal('area_produtiva', 8, 2)->nullable()->change();
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
            $table->dropColumn('area_total_solo_lado1');
            $table->dropColumn('area_total_solo_lado2');
            $table->dropColumn('area_produtiva_lado1');
            $table->dropColumn('area_produtiva_lado2');
            $table->dropColumn('area_disponivel_expansao_lado1');
            $table->dropColumn('area_disponivel_expansao_lado2');
            $table->bigInteger('area_total_solo')->nullable()->change();
            $table->bigInteger('area_produtiva')->nullable()->change();
        });
    }
}
