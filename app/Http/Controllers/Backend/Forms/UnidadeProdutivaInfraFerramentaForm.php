<?php

namespace App\Http\Controllers\Backend\Forms;

use Kris\LaravelFormBuilder\Form;
use App\Enums\SituacaoInfraFerramentaEnum;

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
            'infra_ferramenta_id',
            'select',
            [
                'label' => 'Nome',
                'choices' => \App\Models\Core\InfraFerramentaModel::all()->pluck('nome', 'id')->toArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Nome'])
            ]        
        )->add('quantidade', 'number', [
            'label' => 'Quantidade (unidades)',            
            'wrapper' => ['class' => 'form-group row todos']
        ])->add(
            'situacao', 
            'select',
            [
                'label' => 'Situação',
                'choices' => SituacaoInfraFerramentaEnum::toSelectArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Nome'])        
        ]);
    }
}
