<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Http\Controllers\Backend\Forms\UnidadeProdutivaProdutorForm;
use App\Models\Core\UnidadeProdutivaModel;
use App\Models\Core\ProdutorUnidadeProdutivaModel;
use DataTables;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait UnidadeProdutivaProdutoraTrait
{
    /**
     * Edição
     *
     * @param  FormBuilder $formBuilder
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  mixed $pivot é o registro de relacionamento entre "produtor" vs "unidade produtiva" (tabela produtor_unidade_produtiva)
     * @return void
     */
    public function editProdutor(FormBuilder $formBuilder, UnidadeProdutivaModel $unidadeProdutiva, $pivot)
    {
        $form = $formBuilder->create(UnidadeProdutivaProdutorForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.update-produtor', compact('unidadeProdutiva', 'pivot')),
            'class' => 'needs-validation',
            'novalidate' => true,
            'model' => ProdutorUnidadeProdutivaModel::find($pivot)
        ]);

        $title = 'Editar Produtor/a';

        $back = route('admin.core.unidade_produtiva.search-produtor', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.add-produtor', compact('form', 'title', 'back'));
    }

    /**
     * Edição - POST
     *
     * @param  Request $request
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  mixed $pivot é o registro de relacionamento entre "produtor" vs "unidade produtiva" (tabela produtor_unidade_produtiva)
     * @return void
     */
    public function updateProdutor(Request $request, UnidadeProdutivaModel $unidadeProdutiva, $pivot)
    {
        $data = $request->only(['produtor_id', 'contato', 'tipo_posse_id']);

        try {
            ProdutorUnidadeProdutivaModel::find($pivot)->update($data);
        } catch (\Exception $e) {
            return redirect()->route('admin.core.unidade_produtiva.search-produtor', compact('unidadeProdutiva'))->withFlashDanger('A Produtora já possuí a Unidade Produtiva selecionada!');
        }

        return redirect()->route('admin.core.unidade_produtiva.search-produtor', compact('unidadeProdutiva'))->withFlashSuccess('Produtor/a atualizada com sucesso!');
    }

    /**
     * Listagem de produtoras vinculadas a unidade produtiva
     */
    public function searchProdutora(UnidadeProdutivaModel $unidadeProdutiva)
    {
        $result=UnidadeProdutivaModel::find($unidadeProdutiva->id)->produtores()->get();
        $urlDatatable = route('admin.core.unidade_produtiva.datatableSearchProdutor', ["unidadeProdutiva" => $unidadeProdutiva]);
        return view('backend.core.unidade_produtiva.search-produtor', compact('urlDatatable'));
    }

    /**
     * API Datatable "datatableSearchProdutora()"
     */
    public function datatableSearchProdutora(UnidadeProdutivaModel $unidadeProdutiva)
    {

        return DataTables::of(UnidadeProdutivaModel::find($unidadeProdutiva->id)->produtores()->get())
        ->editColumn('uid', function ($row) {
            return $row->uid;
        })->addColumn('tipoPosse', function ($row) {
            return @$row->pivot->tipoPosse->nome;
        })->addColumn('actions', function ($row) {
            $params = ['pivot' => $row->pivot->id, 'unidadeProdutiva' => $row->pivot->unidade_produtiva_id];
            
            $externalEditUrl = route('admin.core.produtor.edit', $row->id);
            $relationEditUrl = route('admin.core.unidade_produtiva.edit-produtor', $params);
            $deleteUrl = route('admin.core.unidade_produtiva.delete-produtor', $params);
            return view('backend.components.form-actions.index', compact('externalEditUrl', 'relationEditUrl', 'deleteUrl', 'row'));
        })->make(true);
    }

    /**
     * Desvincular/remover uma unidade produtiva vs produtor (tabela produtor_unidade_produtiva)
     */
    public function deleteProdutor(UnidadeProdutivaModel $unidadeProdutiva, $pivot)
    {
        ProdutorUnidadeProdutivaModel::find($pivot)->delete();

        if (UnidadeProdutivaModel::where('id', $unidadeProdutiva->id)->first()) {
            return redirect()->route('admin.core.unidade_produtiva.search-produtor', compact('unidadeProdutiva'))->withFlashSuccess('Produtor/a removido/a com sucesso!');
        } else {
            return redirect()->route('admin.core.produtor.search-unidade-produtiva-redirect');
        }
    }
}
