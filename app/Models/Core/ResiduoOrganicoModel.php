<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResiduoOrganicoModel extends Model
{
    use SoftDeletes;

    protected $table = 'residuo_organicos';

    protected $fillable = ['nome'];
}
