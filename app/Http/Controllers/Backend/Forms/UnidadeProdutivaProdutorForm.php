<?php

namespace App\Http\Controllers\Backend\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Unidades produtivas vinculadas ao produtor
 */
class UnidadeProdutivaProdutorForm extends Form
{
    public function buildForm()
    {
        $this->add(
            'produtor_id',
            'select',
            [
                'label' => 'Nome do/a Produtor/a',
                'choices' => \App\Models\Core\ProdutorModel::pluck('nome', 'id')->sortBy('nome')->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Nome do/a Produtor/a'])
            ]
        )->add(
            'tipo_posse_id',
            'select',
            [
                'label' => 'Tipo de Relação',
                'choices' => \App\Models\Core\TipoPosseModel::pluck('nome', 'id')->sortBy('nome')->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Tipo de Relação'])
            ]
        );
    }
}
