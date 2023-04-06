<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\ProdutorModel;
use App\Models\Core\RegiaoModel;
use App\Models\Core\UnidadeProdutivaModel;
use Illuminate\Http\Request;

class ProdutorController extends Controller
{

    /**
     * Checa se o nome do produtor já existe
     *
     * Utilizado no momento de criar/editar um produtor
     *
     * @return void
     */
    public function verificaNomeExiste(Request $request)
    {
        $request->validate([
            'nome_produtor' => 'unique:produtores',
        ], [
            'O CPF informado já encontra-se utilizado pelo produtor/a "' . @ProdutorModel::withoutGlobalScopes()->where("cpf", $request->cpf)->first()->nome . '".'
        ]);

        $data = $request->only(
            'nome'
        );

        $produtor = ProdutorModel::where('nome', $data['nome'])->first();
        
        return $produtor ? 'true' : 'false';
    }
}
