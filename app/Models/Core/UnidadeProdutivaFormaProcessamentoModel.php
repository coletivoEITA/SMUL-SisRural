<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;

/**
 * Utilizado na Unidade Produtiva
 */
class UnidadeProdutivaFormaProcessamentoModel extends Pivot
{
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'unidade_produtiva_forma_processamento';

    protected $fillable = ['id', 'unidade_produtiva_id', 'forma_processamento_id', 'deleted_at'];

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if ($model->id)
                return;

            $model->id = (string) Uuid::generate(4);
        });
    }
}
