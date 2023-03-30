<?php

namespace App\Models\Core;

use App\Models\Core\CnaeProdutoModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadeProdutivaCnaeProdutoModel extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'unidade_produtiva_cnae_produtos';

    protected $fillable = ['id', 'quantidade', 'observacao', 'unidade_produtiva_id', 'cnae_produto_id', 'created_by', 'updated_by', 'deleted_by'];

    // protected $keyType = 'string';

    // protected static function boot()
    // {
    //     parent::boot();

    //     self::creating(function ($model) {
    //         if ($model->id)
    //             return;

    //         $model->id = (string) Uuid::generate(4);
    //     });
    // }

    public function unidadeProdutiva()
    {
        return $this->belongsTo(UnidadeProdutivaModel::class, 'unidade_produtiva_id');
    }

    public function cnaeProduto()
    {
        return $this->belongsTo(CnaeProdutoModel::class, 'cnae_produto_id');
    }
}
