<?php

namespace App\Models\Core;

use App\Models\Core\InfraFerramentaModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeProdutivaInfraFerramentaModel extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'unidade_produtiva_infra_ferramentas';

    protected $fillable = ['id', 'quantidade', 'situacao', 'unidade_produtiva_id', 'infra_ferramenta_id', 'created_by', 'updated_by', 'deleted_by'];

    public function unidadeProdutiva()
    {
        return $this->belongsTo(UnidadeProdutivaModel::class, 'unidade_produtiva_id');
    }

    public function infraFerramenta()
    {
        return $this->belongsTo(InfraFerramentaModel::class, 'infra_ferramenta_id');
    }
}
