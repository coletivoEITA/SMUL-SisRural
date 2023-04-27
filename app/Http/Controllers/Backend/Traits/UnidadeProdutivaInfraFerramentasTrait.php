<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Helpers\General\SoftDeleteHelper;
use App\Http\Controllers\Backend\Forms\UnidadeProdutivaInfraFerramentaForm;
use App\Models\Core\UnidadeProdutivaInfraFerramentaModel;
use App\Models\Core\UnidadeProdutivaModel;
use DataTables;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait UnidadeProdutivaInfraFerramentasTrait
{
    /**
     * Listagem de uso de solo de uma unidade produtiva
     *
     * @param  mixed $unidadeProdutiva
     * @param  mixed $request
     * @return void
     */
    public function infraFerramentasIndex(UnidadeProdutivaModel $unidadeProdutiva, Request $request)
    {
        $title = 'Infraestrutura e ferramentas existentes';
        $addUrl = route('admin.core.unidade_produtiva.infra_ferramentas.create', ["unidadeProdutiva" => $unidadeProdutiva]);
        $urlDatatable = route('admin.core.unidade_produtiva.infra_ferramentas.datatable', ["unidadeProdutiva" => $unidadeProdutiva]);
        $labelAdd = 'Adicionar Infraestrutura/Ferramentas';

        return view('backend.core.unidade_produtiva.infra_ferramentas.index', compact('addUrl', 'urlDatatable', 'title', 'labelAdd'));
    }

    /**
     * API Datatable "infraFerramentasIndex()"
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function infraFerramentasDatatable(UnidadeProdutivaModel $unidadeProdutiva)
    {
        return DataTables::of($unidadeProdutiva->infraFerramentas()->get())
            ->addColumn('nome', function ($row) {
                return $row->infraFerramenta->nome;
            })->addColumn('actions', function ($row) use ($unidadeProdutiva) {
                $params = ['unidadeProdutiva' => $row->unidade_produtiva_id, 'unidadeProdutivaInfraFerramenta' => $row->id];
                $editUrl = route('admin.core.unidade_produtiva.infra_ferramentas.edit', $params);
                $deleteUrl = route('admin.core.unidade_produtiva.infra_ferramentas.destroy', $params);

                return view('backend.components.form-actions.index', ['editUrl' => $editUrl, 'deleteUrl' => $deleteUrl, 'row' => $unidadeProdutiva]);
            })
            ->make(true);
    }

    /**
     * Cadastro uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  FormBuilder $formBuilder
     * @return void
     */
    public function infraFerramentasCreate(UnidadeProdutivaModel $unidadeProdutiva, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaInfraFerramentaForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.infra_ferramentas.store', ['unidadeProdutiva' => $unidadeProdutiva]),
            'class' => 'needs-validation',
            'novalidate' => true,
        ]);

        $title = 'Cadastrar Infraestrutura/Ferramenta';

        $back = route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.infra_ferramentas.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Cadastro uso do solo - POST
     *
     * @param  Request $request
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function infraFerramentasStore(Request $request, UnidadeProdutivaModel $unidadeProdutiva)
    {
        $data = $request->only(['quantidade', 'situacao', 'infra_ferramenta_id', 'created_by', 'updated_by', 'deleted_by']);
        $data['unidade_produtiva_id'] = $unidadeProdutiva->id;

        $unidadeProdutivaInfraFerramenta = UnidadeProdutivaInfraFerramentaModel::create($data);

        return redirect()->route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'))->withFlashSuccess('Infraestrutura/Ferramenta criada com sucesso!');
    }

    /**
     * Edição uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  mixed $unidadeProdutivaInfraFerramenta
     * @param  FormBuilder $formBuilder
     * @return void
     */
    public function infraFerramentasEdit(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaInfraFerramentaModel $unidadeProdutivaInfraFerramenta, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaInfraFerramentaForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.infra_ferramentas.update', ['unidadeProdutiva' => $unidadeProdutiva, 'unidadeProdutivaInfraFerramenta' => $unidadeProdutivaInfraFerramenta]),
            'class' => 'needs-validation',
            'novalidate' => true,
            'model' => $unidadeProdutivaInfraFerramenta,
        ]);

        $title = 'Editar Infraestrutura/Ferramenta';

        $back = route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.infra_ferramentas.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Atualização uso do solo - POST
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  Request $request
     * @param  UnidadeProdutivaInfraFerramentaModel $unidadeProdutivaInfraFerramenta
     * @return void
     */
    public function infraFerramentasUpdate(UnidadeProdutivaModel $unidadeProdutiva, Request $request, UnidadeProdutivaInfraFerramentaModel $unidadeProdutivaInfraFerramenta)
    {
        $data = $request->only(['quantidade', 'situacao', 'infra_estrutura_id', 'unidade_produtiva_id', 'created_by', 'updated_by', 'deleted_by']);

        $unidadeProdutivaInfraFerramenta->update($data);

        return redirect()->route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'))->withFlashSuccess('Infraestrutura/Ferramenta atualizada com sucesso!');
    }

    /**
     * Remover uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  UnidadeProdutivaInfraFerramentaModel $unidadeProdutivaInfraFerramenta
     * @return void
     */
    public function infraFerramentasDestroy(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaInfraFerramentaModel $unidadeProdutivaInfraFerramenta)
    {
        $unidadeProdutivaInfraFerramenta->delete();

        return redirect()->route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'))->withFlashSuccess('Infraestrutura/Ferramenta deletada com sucesso');
    }
}
