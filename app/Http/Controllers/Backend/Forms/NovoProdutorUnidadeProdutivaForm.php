<?php

namespace App\Http\Controllers\Backend\Forms;

use Illuminate\Support\Facades\Auth;
use App\Enums\ProdutorStatusEnum;
use Kris\LaravelFormBuilder\Form;

/**
 * Formulário cadastro rápido do Produtor / Unidade Produtiva
 *
 * Possuí os dados mínimos para conseguir inserir um Produtor/Unidade Produtiva
 */
class NovoProdutorUnidadeProdutivaForm extends Form
{
    public function buildForm()
    {
        /**
         * Bloco do Produtor
         */
        $this->add('card-produtor-start', 'card-start', [
            'title' => 'Produtor/a',
            'titleTag'=>'h2'
        ])->add('nome_produtor', 'text', [
            'label' => 'Nome Completo',
            'rules' => 'required',
            'error' => __('validation.required', ['attribute' => 'Nome'])
        ])->add('cpf', 'text', [
            'label' => 'CPF (Cadastro de Pessoa Física)',
            'attr' => [
                'class' => 'form-control req-cpf',
                '_mask' => '999.999.999-99',
            ],
            'error' => 'CPF inválido'
        ])->add('telefone_1', 'text', [
            'label' => 'Telefone 1',
            'attr' => [
                '_mask' => '99 99999999?9',
            ],
        ])->add('telefone_2', 'text', [
            'label' => 'Telefone 2',
            'attr' => [
                '_mask' => '99 99999999?9',
            ],
        ])->add(
            'status',
            'select',
            [
                'label' => 'Status',
                'choices' => ProdutorStatusEnum::toSelectArray(),
                'empty_value' => 'Selecione',
                'rules' => 'required',
                'error' => __('validation.required', ['attribute' => 'Status']),
            ]            
        )->add('card-produtor-end', 'card-end', []);

        /**
         * Bloco da Unidade Produtiva
         */
        $this->add('card-unidade-produtiva-start', 'card-start', [
            'title' => 'Unidade Produtiva',
        ])->add('fl_unidade_produtiva', 'checkbox', [
            'label' => 'Relacionar com uma Unidade Produtiva já existente.',
        ])->add(
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

        /**
         * Bloco para seleção de uma Unidade Produtiva -> Caso a resposta do "fl_unidade_produtiva" = true (Relacionar uma unidade produtiva já existente)
         */
        $this->add('fieldset-unidade-produtiva-start', 'fieldset-start', ['id' => 'com-unidade-produtiva'])
            ->add(
                'unidade_produtiva_id',
                'select',
                [
                    'label' => 'Nome da Propriedade',
                    'choices' => \App\Models\Core\UnidadeProdutivaModel::pluck('nome', 'id')->sortBy('nome')->toArray(),
                    'empty_value' => 'Selecione',
                    'error' => __('validation.required', ['attribute' => 'Nome da Propriedade'])
                ]
            )->add('fieldset-unidade-produtiva-end', 'fieldset-end');


        /**
         * Bloco para cadastro de uma Unidade Produtiva -> Caso a resposta do "fl_unidade_produtiva" = false (Relacionar uma unidade produtiva já existente)
         */
        $this->add('fieldset-unidade-produtiva-novo-start', 'fieldset-start', ['id' => 'sem-unidade-produtiva'])
            ->add('nome_unidade_produtiva', 'text', [
                'label' => 'Nome da Unidade Produtiva',
            ])->add('cep', 'text', [
                'label' => 'CEP (Código de Endereçamento Postal)',
                'attr' => [
                    '_mask' => '99999-999',
                ],
            ])->add('endereco', 'text', [
                'label' => 'Endereço',
                //Esse campo é obrigatório, mas é tratado via JS, porque depende da seleção do "fl_unidade_produtiva"
            ])->add('bairro', 'text', [
                'label' => 'Bairro',
            ])->add('subprefeitura', 'select', [
                'label' => 'Distrito',
                'empty_value' => 'Selecione',
                'choices' => ['Maricá' => 'Maricá', 'Ponta Negra' => 'Ponta Negra', 'Inoã' => 'Inoã', 'Itaipuaçu' => 'Itaipuaçu'],
            ]);

            // Caso não tenha UF/municipio, verifica se usuário está em apenas uma UF/Município. Caso sim, preenche campo UF/Municipio automaticamente.
            $selected_uf = [];
            $selected_municipio = NULL;
            if(!isset($this->model['estado_id'])){
                $user = Auth::user();
                if($uf = $user->getDefaultUF()){
                    $selected_uf = ["selected" => $uf];
                }            
            }
            if(!isset($this->model['cidade_id'])){
                $selected_municipio = $user->getDefaultMunicipio();
            }          

            $this->add(
                'estado_id',
                'select',
                [
                    'label' => 'Estado',
                    'empty_value' => 'Selecione',
                    'choices' => \App\Models\Core\EstadoModel::orderByRaw('FIELD(uf, "SP") DESC, nome')->pluck('nome', 'id')->toArray(),
                    'error' => __('validation.required', ['attribute' => 'Estado'])
                ] + $selected_uf
            )->add(
                'cidade_id',
                'select',
                [
                    'label' => 'Município',
                    'empty_value' => 'Selecione',
                    'choices' => @$this->model->estado_id ? \App\Models\Core\CidadeModel::where('estado_id', @$this->model->estado_id)->pluck('nome', 'id')->sortBy('nome')->   Array() : [],
                    'error' => __('validation.required', ['attribute' => 'Município']),
                    'attr' => ['municipio_selected' => $selected_municipio]
                ]
            )->add('fieldset-unidade-produtiva-novo-end', 'fieldset-end')->add('card-unidade-produtiva-end', 'card-end');


        /**
         * Bloco das Coordenadas (lat/lng)
         *
         * Elas não são obrigatórias porque caso o usuário não informe, o sistema pega a LAT/LNG da Cidade selecionada
         */
        $this->add('card-coordenadas', 'card-start', [
            'id' => 'card-coordenadas',
            'title' => 'Coordenadas',
            'titleTag' => 'h2'
        ])->add('lat', 'text', [
            'label' => 'Latitude',
            'error' => __('validation.required', ['attribute' => 'Latitude']),
        ])->add('lng', 'text', [
            'label' => 'Longitude',
            'error' => __('validation.required', ['attribute' => 'Longitude']),
        ])->add('card-coordenadas-end', 'card-end', []);
    }
}
