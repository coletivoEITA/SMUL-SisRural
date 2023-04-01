<?php

namespace App\Models\Core;

use App\Models\Core\CulturaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeProdutivaCulturaModel extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'unidade_produtiva_culturas';

    protected $fillable = ['id', 'quantidade', 'observacao', 'unidade_produtiva_id', 'cultura_id', 'created_by', 'updated_by', 'deleted_by'];

    public function unidadeProdutiva()
    {
        return $this->belongsTo(UnidadeProdutivaModel::class, 'unidade_produtiva_id');
    }

    public function cultura()
    {
        return $this->belongsTo(CulturaModel::class, 'cultura_id');
    }
}
