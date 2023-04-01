<?php

namespace App\Http\Controllers\Backend\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Formulário para cadastro de Culturas - Unidade Produtiva
 */
class UnidadeProdutivaCulturaForm extends Form
{
    public function buildForm()
    {
        $this->add(
            'unidade_produtiva_id',
            'hidden',
            ['label' => 'Unidade Produtiva']
        )->add(
            'cultura_id',
            'select',
            [
                'label' => 'Cultura',
                'choices' => \App\Models\Core\CulturaModel::all()->pluck('nome', 'id')->sortBy('nome')->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Cultura'])
            ]        
        )->add('quantidade', 'number', [
            'label' => 'Quantidade (unidades)',            
            'wrapper' => ['class' => 'form-group row todos']
        ])->add('observacao', 'text', [
            'label' => 'Situação produtiva e observações',
            'wrapper' => ['class' => 'form-group row todos']
        ]);
    }
}
