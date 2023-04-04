<?php

namespace App\Http\Controllers\Backend\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Formulário para cadastro de Infra/Ferramentas - Unidade Produtiva
 */
class UnidadeProdutivaInfraFerramentaForm extends Form
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
                'label' => 'Nome',
                'choices' => \App\Models\Core\InfraFerramentaModel::all()->pluck('nome', 'id')->sort()->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Nome'])
            ]        
        )->add('quantidade', 'number', [
            'label' => 'Quantidade (unidades)',            
            'wrapper' => ['class' => 'form-group row todos']
        ])->add('situacao', 'text', [
            'label' => 'Situação produtiva e observações',
            'wrapper' => ['class' => 'form-group row todos']
        ]);
    }
}
