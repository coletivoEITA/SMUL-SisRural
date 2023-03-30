<?php

namespace App\Http\Controllers\Backend\Forms;

use Kris\LaravelFormBuilder\Form;

/**
 * Formulário para cadastro de Culturas - Produtos com Base no CNAE - Unidade Produtiva
 */
class UnidadeProdutivaCnaeProdutoForm extends Form
{
    public function buildForm()
    {
        $this->add(
            'unidade_produtiva_id',
            'hidden',
            ['label' => 'Unidade Produtiva']
        )->add(
            'cnae_produto_id',
            'select',
            [
                'label' => 'Cultura',
                'choices' => \App\Models\Core\CnaeProdutoModel::all()->pluck('nome', 'id')->sortBy('nome')->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Cultura'])
            ]        
        )->add('quantidade', 'number', [
            'label' => 'Quantidade (unidades)',            
            'wrapper' => ['class' => 'form-group row todos']
        ])->add('observacao', 'text', [
            'label' => 'Observação',            
            'wrapper' => ['class' => 'form-group row todos']
        ]);
    }
}
