<?php

namespace App\Http\Controllers\Backend\Traits;

use App\Helpers\General\SoftDeleteHelper;
use App\Http\Controllers\Backend\Forms\UnidadeProdutivaCnaeProdutoForm;
use App\Models\Core\UnidadeProdutivaCnaeProdutoModel;
use App\Models\Core\UnidadeProdutivaModel;
use DataTables;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;

trait UnidadeProdutivaCnaeProdutosTrait
{
    /**
     * Listagem de uso de solo de uma unidade produtiva
     *
     * @param  mixed $unidadeProdutiva
     * @param  mixed $request
     * @return void
     */
    public function produtosIndex(UnidadeProdutivaModel $unidadeProdutiva, Request $request)
    {
        $title = 'Culturas existentes';
        $addUrl = route('admin.core.unidade_produtiva.produtos.create', ["unidadeProdutiva" => $unidadeProdutiva]);
        $urlDatatable = route('admin.core.unidade_produtiva.produtos.datatable', ["unidadeProdutiva" => $unidadeProdutiva]);
        $labelAdd = 'Adicionar Cultura';

        return view('backend.core.unidade_produtiva.produtos.index', compact('addUrl', 'urlDatatable', 'title', 'labelAdd'));
    }

    /**
     * API Datatable "produtosIndex()"
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function produtosDatatable(UnidadeProdutivaModel $unidadeProdutiva)
    {
        return DataTables::of($unidadeProdutiva->produtos()->get())
            ->addColumn('produto', function ($row) {
                return $row->cnaeProduto->nome;            
            })->addColumn('actions', function ($row) use ($unidadeProdutiva) {
                $params = ['unidadeProdutiva' => $row->unidade_produtiva_id, 'unidadeProdutivaCnaeProduto' => $row->id];
                $editUrl = route('admin.core.unidade_produtiva.produtos.edit', $params);
                $deleteUrl = route('admin.core.unidade_produtiva.produtos.destroy', $params);

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
    public function produtosCreate(UnidadeProdutivaModel $unidadeProdutiva, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaCnaeProdutoForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.produtos.store', ['unidadeProdutiva' => $unidadeProdutiva]),
            'class' => 'needs-validation',
            'novalidate' => true,
        ]);

        $title = 'Cadastrar Cultura';

        $back = route('admin.core.unidade_produtiva.produtos.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.produtos.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Cadastro uso do solo - POST
     *
     * @param  Request $request
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function produtosStore(Request $request, UnidadeProdutivaModel $unidadeProdutiva)
    {
        $data = $request->only(['quantidade', 'observacao', 'cnae_produto_id', 'created_by', 'updated_by', 'deleted_by']);
        $data['unidade_produtiva_id'] = $unidadeProdutiva->id;

        $unidadeProdutivaCnaeProduto = UnidadeProdutivaCnaeProdutoModel::create($data);

        return redirect()->route('admin.core.unidade_produtiva.produtos.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura criada com sucesso!');
    }

    /**
     * Edição uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  mixed $unidadeProdutivaCnaeProduto
     * @param  FormBuilder $formBuilder
     * @return void
     */
    public function produtosEdit(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaCnaeProdutoModel $unidadeProdutivaCnaeProduto, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(UnidadeProdutivaCnaeProdutoForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.produtos.update', ['unidadeProdutiva' => $unidadeProdutiva, 'unidadeProdutivaCnaeProduto' => $unidadeProdutivaCnaeProduto]),
            'class' => 'needs-validation',
            'novalidate' => true,
            'model' => $unidadeProdutivaCnaeProduto,
        ]);

        $title = 'Editar cultura';

        $back = route('admin.core.unidade_produtiva.produtos.index', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.produtos.create_update', compact('form', 'title', 'back'));
    }

    /**
     * Atualização uso do solo - POST
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  Request $request
     * @param  UnidadeProdutivaCnaeProdutoModel $unidadeProdutivaCnaeProduto
     * @return void
     */
    public function produtosUpdate(UnidadeProdutivaModel $unidadeProdutiva, Request $request, UnidadeProdutivaCnaeProdutoModel $unidadeProdutivaCnaeProduto)
    {
        $data = $request->only(['quantidade', 'observacao', 'cnae_produto_id', 'unidade_produtiva_id', 'created_by', 'updated_by', 'deleted_by']);

        $unidadeProdutivaCnaeProduto->update($data);

        return redirect()->route('admin.core.unidade_produtiva.produtos.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura atualizado com sucesso!');
    }

    /**
     * Remover uso do solo
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  UnidadeProdutivaCnaeProdutoModel $unidadeProdutivaCnaeProduto
     * @return void
     */
    public function produtosDestroy(UnidadeProdutivaModel $unidadeProdutiva, UnidadeProdutivaCnaeProdutoModel $unidadeProdutivaCnaeProduto)
    {
        $unidadeProdutivaCnaeProduto->delete();

        return redirect()->route('admin.core.unidade_produtiva.produtos.index', compact('unidadeProdutiva'))->withFlashSuccess('Cultura deletado com sucesso');
    }
}
