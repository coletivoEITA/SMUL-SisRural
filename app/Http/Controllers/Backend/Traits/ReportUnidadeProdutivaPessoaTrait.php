<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Models\Core\ColaboradorModel;
use App\Models\Core\UnidadeProdutivaModel;
use Illuminate\Http\Request;

trait ReportUnidadeProdutivaPessoaTrait
{
    public function unidadeProdutivaPessoaData(Request $request)
    {
        \Log::channel('sisrural')->info('Download CSV - Pessoa');

        try {
            $data = $request->only(
                [
                    'dt_ini', 'dt_end', 'dominio_id', 'unidade_operacional_id', 'estado_id', 'cidade_id', 'regiao_id', 'produtor_id', 'unidade_produtiva_id',
                    'atuacao_dominio_id', 'atuacao_unidade_operacional_id', 'atuacao_tecnico_id',
                    'checklist_id', 'certificacao_id', 'solo_categoria_id', 'area', 'genero_id', 'status_unidade_produtiva'
                ]
            );

            //Query Base
            //whereHas porque não existe "permissionScope" configurado para o Colaborador, sem ele vai retornar todos os colaboradores do sistema
            $query = ColaboradorModel::with(['relacao', 'dedicacao', 'unidadeProdutiva:id,uid'])
                ->whereHas('unidadeProdutiva');

            //Abrangência territorial (OR entre os pares, AND entre os outros blocos de filtro)
            $query->where(function ($query) use ($data) {
                $this->service->queryDominios($query, 'unidadeProdutiva.unidadesOperacionaisNS', @$data['dominio_id']);
                $this->service->queryUnidadesOperacionais($query, 'unidadeProdutiva.unidadesOperacionaisNS', @$data['unidade_operacional_id']);
                $this->service->queryEstados($query, 'unidadeProdutiva', @$data['estado_id']);
                $this->service->queryCidades($query, 'unidadeProdutiva', @$data['cidade_id']);
                $this->service->queryRegioes($query, 'unidadeProdutiva', @$data['regiao_id']);
                $this->service->queryProdutores($query, 'unidadeProdutiva.produtores', @$data['produtor_id']);
                $this->service->queryUnidadesProdutivas($query, 'unidadeProdutiva', @$data['unidade_produtiva_id']);
            });

            //Atuação
            $query->where(function ($query) use ($data) {
                $this->service->queryAtuacaoProdutor($query, 'unidadeProdutiva.produtores', @$data['atuacao_dominio_id'], @$data['atuacao_unidade_operacional_id'], @$data['atuacao_tecnico_id'], @$data['dt_ini'], @$data['dt_end']);
                $this->service->queryAtuacaoUnidadeProdutiva($query, 'unidadeProdutiva', @$data['atuacao_dominio_id'], @$data['atuacao_unidade_operacional_id'], @$data['atuacao_tecnico_id'], @$data['dt_ini'], @$data['dt_end']);
                $this->service->queryAtuacaoCadernoDeCampo($query, 'unidadeProdutiva.produtores.cadernos', @$data['atuacao_dominio_id'], @$data['atuacao_unidade_operacional_id'], @$data['atuacao_tecnico_id'], @$data['dt_ini'], @$data['dt_end']);
                $this->service->queryAtuacaoFormulario($query, 'unidadeProdutiva.checklists', @$data['atuacao_dominio_id'], @$data['atuacao_unidade_operacional_id'], @$data['atuacao_tecnico_id'], @$data['dt_ini'], @$data['dt_end']);
                $this->service->queryAtuacaoPlanoAcao($query, 'unidadeProdutiva.planoAcoes', @$data['atuacao_dominio_id'], @$data['atuacao_unidade_operacional_id'], @$data['atuacao_tecnico_id'], @$data['dt_ini'], @$data['dt_end']);
            });

            //Filtros Adicionais
            $this->service->queryChecklists($query, 'unidadeProdutiva.checklists', @$data['checklist_id'], @$data['dt_ini'], @$data['dt_end']);
            $this->service->queryCertificacoes($query, 'unidadeProdutiva', 'certificacoes', @$data['certificacao_id']);
            $this->service->querySoloCategoria($query, 'unidadeProdutiva.solosCategoria', 'unidadeProdutiva.caracterizacoes', @$data['solo_categoria_id']);
            $this->service->queryArea($query, 'unidadeProdutiva', @$data['area']);
            $this->service->queryGenero($query, 'unidadeProdutiva.produtores', @$data['genero_id']);
            $this->service->queryStatusUnidadeProdutiva($query, 'unidadeProdutiva', @$data['status_unidade_produtiva']);

            //CSV
            return $this->downloadCsv(
                $this->filename('unidades_produtivas_pessoas'),
                $query,
                [
                    'ID',
                    'ID da Unidade Produtiva',
                    'Nome Completo',
                    'Relação',
                    'CPF',
                    'Função',
                    'Dedicação',
                ],
                function ($handle, $v) {
                    fputcsv(
                        $handle,
                        $this->removeBreakLine(
                            [
                                $v->uid, //id, //'ID',
                                $this->privateData($v->unidadeProdutiva->uid), //$v->unidade_produtiva_id, //'ID DA UNIDADE PRODUTIVA',
                                $this->privateData($v->nome), //'Nome Completo',
                                $v->relacao ? $v->relacao->nome : null, //'Relação',
                                $this->privateData($this->scapeInt($v->cpf)), //'CPF',
                                $v->funcao, //'Função',
                                $v->dedicacao ? $v->dedicacao->nome : null, //'Dedicação',
                            ]
                        ),
                        ';'
                    );
                }
            );
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
