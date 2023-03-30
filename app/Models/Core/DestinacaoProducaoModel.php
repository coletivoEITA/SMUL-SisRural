<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Utilizado pela Unidade Produtiva (UnidadeProdutivaModel)
 */
class DestinacaoProducaoModel extends Model
{
    use SoftDeletes;

    protected $table = 'destinacao_producao';

    protected $fillable = ['nome'];
}
