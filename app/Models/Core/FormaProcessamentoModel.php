<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaProcessamentoModel extends Model
{
    use SoftDeletes;

    protected $table = 'forma_processamento';

    protected $fillable = ['nome'];
}
