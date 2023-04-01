<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Helpers\General\SoftDeleteHelper;
use App\Http\Controllers\Backend\Forms\UnidadeProdutivaCulturaForm;
use App\Models\Core\UnidadeProdutivaCulturaModel;
use App\Models\Core\UnidadeProdutivaModel;
use DataTables;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait UnidadeProdutivaCulturasTrait
{
    /**
     * Listagem de uso de solo de uma unidade produtiva
     *
     * @param  mixed $unidadeProdutiva
     * @param  mixed $request
     * @return void
     */
    public function culturasIndex(UnidadeProdutivaModel $unidadeProdutiva, Request $request)
    {
        $title = 'Culturas existentes';
        $addUrl = route('admin.core.unidade_produtiva.culturas.create', ["unidadeProdutiva" => $unidadeProdutiva]);
        $urlDatatable = route('admin.core.unidade_produtiva.culturas.datatable', ["unidadeProdutiva" => $unidadeProdutiva]);
        $labelAdd = 'Adicionar Cultura';

        return view('backend.core.unidade_produtiva.culturas.index', compact('addUrl', 'urlDatatable', 'title', 'labelAdd'));
    }

    /**
     * API Datatable "culturasIndex()"
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function culturasDatatable(UnidadeProdutivaModel $unidadeProdutiva)
    {
        return DataTables::of($unidadeProdutiva->culturas()->get())
            ->addColumn('produto', function ($row) {
                return $row->cultura->nome;            
            })->addColumn('actions', function ($row) use ($unidadeProdutiva) {
                $params = ['unidadeProdutiva' => $row->unidade_produtiva_id, 'unidadeProdutivaCultura' => $row->id];
                $editUrl = route('admin.core.unidade_produtiva.culturas.edit', $params);
                $deleteUrl = route('admin.core.unidade_produtiva.culturas.destroy', $params);

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
    public function culturasCreate(UnidadeProdutivaModel $unidadeProdutiva, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaCulturaForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.culturas.store', ['unidadeProdutiva' => $unidadeProdutiva]),
            'class' => 'needs-validation',
            'novalidate' => true,
        ]);

        $title = 'Cadastrar Cultura';

        $back = route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.culturas.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Cadastro uso do solo - POST
     *
     * @param  Request $request
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function culturasStore(Request $request, UnidadeProdutivaModel $unidadeProdutiva)
    {
        $data = $request->only(['quantidade', 'observacao', 'cultura_id', 'created_by', 'updated_by', 'deleted_by']);
        $data['unidade_produtiva_id'] = $unidadeProdutiva->id;

        $unidadeProdutivaCultura = UnidadeProdutivaCulturaModel::create($data);

        return redirect()->route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura criada com sucesso!');
    }

    /**
     * Edição uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  mixed $unidadeProdutivaCultura
     * @param  FormBuilder $formBuilder
     * @return void
     */
    public function culturasEdit(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaCulturaModel $unidadeProdutivaCultura, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaCulturaForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.culturas.update', ['unidadeProdutiva' => $unidadeProdutiva, 'unidadeProdutivaCultura' => $unidadeProdutivaCultura]),
            'class' => 'needs-validation',
            'novalidate' => true,
            'model' => $unidadeProdutivaCultura,
        ]);

        $title = 'Editar cultura';

        $back = route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.culturas.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Atualização uso do solo - POST
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  Request $request
     * @param  UnidadeProdutivaCulturaModel $unidadeProdutivaCultura
     * @return void
     */
    public function culturasUpdate(UnidadeProdutivaModel $unidadeProdutiva, Request $request, UnidadeProdutivaCulturaModel $unidadeProdutivaCultura)
    {
        $data = $request->only(['quantidade', 'observacao', 'cultura_id', 'unidade_produtiva_id', 'created_by', 'updated_by', 'deleted_by']);

        $unidadeProdutivaCultura->update($data);

        return redirect()->route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura atualizado com sucesso!');
    }

    /**
     * Remover uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  UnidadeProdutivaCulturaModel $unidadeProdutivaCultura
     * @return void
     */
    public function culturasDestroy(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaCulturaModel $unidadeProdutivaCultura)
    {
        $unidadeProdutivaCultura->delete();

        return redirect()->route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura deletado com sucesso');
    }
}
