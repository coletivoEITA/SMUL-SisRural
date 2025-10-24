<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Utilizado pela Unidade Produtiva (UnidadeProdutivaModel)
 */
class TipoHortaModel extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_horta';

    protected $fillable = ['nome'];
}
