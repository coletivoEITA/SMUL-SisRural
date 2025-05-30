<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\General\AppHelper;
use App\Helpers\General\SoftDeleteHelper;
use App\Http\Controllers\Backend\Forms\UnidadeProdutivaForm;
use App\Http\Controllers\Backend\Traits\UnidadeProdutivaArquivosTrait;
use App\Http\Controllers\Backend\Traits\UnidadeProdutivaCulturasTrait;
use App\Http\Controllers\Backend\Traits\UnidadeProdutivaInfraFerramentasTrait;
use App\Http\Controllers\Backend\Traits\UnidadeProdutivaColaboradoresTrait;
use App\Http\Controllers\Backend\Traits\UnidadeProdutivaProdutoraTrait;
use App\Http\Controllers\Controller;
use App\Models\Core\ProdutorModel;
use App\Models\Core\UnidadeProdutivaModel;
use App\Repositories\Backend\Core\UnidadeProdutivaArquivoRepository;
use App\Repositories\Backend\Core\UnidadeProdutivaRepository;
use DataTables;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use App\Services\UnidadeProdutivaService;
use Exception;

# Includes por conta da necessidade de instanciar o ChecklistUnidadeProdutivaRepository
use App\Models\Core\ChecklistModel;
use App\Models\Core\PlanoAcaoModel;
use App\Models\Core\ChecklistUnidadeProdutivaModel;
use \App\Models\Core\PlanoAcaoItemModel;
use App\Models\Core\CadernoRespostaCadernoModel;
use App\Repositories\Backend\Core\ChecklistUnidadeProdutivaRepository;
use App\Repositories\Backend\Core\PlanoAcaoItemRepository;
use App\Repositories\Backend\Core\PlanoAcaoRepository;


class UnidadeProdutivaController extends Controller
{
    use FormBuilderTrait;
    use UnidadeProdutivaColaboradoresTrait;
    use UnidadeProdutivaCulturasTrait;
    use UnidadeProdutivaArquivosTrait;
    use UnidadeProdutivaInfraFerramentasTrait;
    use UnidadeProdutivaProdutoraTrait;

    /**
     * @var UnidadeProdutivaRepository
     */
    protected $repository;
    protected $repositoryArquivo;

    /**
     * @var UnidadeProdutivaService
     */
    protected $service;

    public function __construct(UnidadeProdutivaRepository $repository, UnidadeProdutivaService $service, UnidadeProdutivaArquivoRepository $repositoryArquivo)
    {
        $this->repository = $repository;
        $this->repositoryArquivo = $repositoryArquivo;

        $this->service = $service;
    }

    /**
     * Visualização
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function view(UnidadeProdutivaModel $unidadeProdutiva)
    {
        if(config('app.checklist_dados_adicionais_unidade_produtiva')){
            $checklistUnidadeProdutiva = ChecklistUnidadeProdutivaModel::where('unidade_produtiva_id', $unidadeProdutiva->id)->where('checklist_id', config('app.checklist_dados_adicionais_unidade_produtiva'))->first();
            if($checklistUnidadeProdutiva){
                $categorias = $checklistUnidadeProdutiva->getCategoriasAndRespostasChecklist();
            } else {
                $categorias = NULL;
            }

        } else {
            $categorias = NULL;
        }

        return view('backend.core.unidade_produtiva.view', compact('unidadeProdutiva', 'categorias'));
    }

    /**
     * Dashboard da unidade produtiva
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function dashboard(UnidadeProdutivaModel $unidadeProdutiva)
    {
        return view('backend.core.unidade_produtiva.dashboard', compact('unidadeProdutiva'));
    }

    /**
     * Listagem das unidades produtivas
     *
     * Caso seja passado o produtor, é filtrado apenas as unidades produtivas daquele produtor
     *
     * @param  ProdutorModel $produtor
     * @return void
     */
    public function index(ProdutorModel $produtor)
    {
        $datatableUrl = route('admin.core.unidade_produtiva.datatable', ['produtor' => @$produtor]);

        return view('backend.core.unidade_produtiva.index', compact('datatableUrl'))->withModels($this->repository->get());
    }

    /**
     * API Datatable "index()"
     *
     * @param  ProdutorModel $produtor
     * @param  bool $fl_invalid
     * @return void
     */
    public function datatable(ProdutorModel $produtor)
    {
        $data = @$produtor->id ? $produtor->unidadesProdutivas()->with(['cidade:id,nome', 'estado:id,nome'])->select('unidade_produtivas.*') : UnidadeProdutivaModel::with(['cidade:id,nome', 'estado:id,nome'])->select('unidade_produtivas.*');

        return DataTables::of($data)
            ->addColumn('produtores', function ($row) {
                return join(", ", $row->produtores->pluck('nome')->toArray());
            })->addColumn('actions', function ($row) {
                $dashUrl = route('admin.core.unidade_produtiva.dashboard', $row->id);
                $editUrl = route('admin.core.unidade_produtiva.edit', $row->id);
                $deleteUrl = route('admin.core.unidade_produtiva.destroy', $row->id);
                $viewUrl = route('admin.core.unidade_produtiva.view', $row->id);

                return view('backend.components.form-actions.index', compact('dashUrl', 'editUrl', 'deleteUrl', 'viewUrl', 'row'));
            })->addColumn('ultima_visita', function ($row) {
                $cadernos = $row->cadernos;
                $data_visita = 0;
                foreach( $cadernos as $caderno ){
                    if(isset(CadernoRespostaCadernoModel::where('caderno_id', $caderno->id)->where('template_pergunta_id', 1)->first()->resposta)){
                        $data_visita_caderno = CadernoRespostaCadernoModel::where('caderno_id', $caderno->id)->where('template_pergunta_id', 1)->first()->resposta;
                        $data_visita_caderno = strtotime($data_visita_caderno);
                        if($data_visita_caderno > $data_visita){
                            $data_visita = $data_visita_caderno;
                        }
                        if( $data_visita != 0 ){
                            return date("d/m/Y",$data_visita);
                        } else {
                            return "";
                        }
                    } else {
                        return "";
                    }
                }

            })->filterColumn('produtores', function ($query, $keyword) {
                if ($keyword) {
                    $query->whereHas('produtores', function ($q) use ($keyword) {
                        $q->where('produtores.nome', 'like', '%' . $keyword . '%');
                    });
                }
            })
            ->rawColumns(['produtores'])
            ->make(true);
    }


    /**
     * Listagem das unidades produtivas que foram cadastradas pelo usuário logado mas que estão inválidas (fora da abrangência do usuário)
     *
     * São dados gerados pelo APP
     *
     * @return void
     */
    public function indexInvalid()
    {
        $datatableUrl = route('admin.core.unidade_produtiva.datatableInvalid');

        return view('backend.core.unidade_produtiva.index_invalid', compact('datatableUrl'))->withModels($this->repository->get());
    }

    /**
     * API Datatable "indexInvalid()"
     *
     * @return void
     */
    public function datatableInvalid()
    {
        $data = UnidadeProdutivaModel::with(['cidade:id,nome', 'estado:id,nome'])->where("fl_fora_da_abrangencia_app", 1)->select('unidade_produtivas.*');

        return DataTables::of($data)
            ->addColumn('produtores', function ($row) {
                return join(", ", $row->produtores->pluck('nome')->toArray());
            })->addColumn('actions', function ($row) {
                $editUrl = route('admin.core.unidade_produtiva.edit', $row->id);
                $deleteUrl = route('admin.core.unidade_produtiva.destroy', $row->id);
                $viewUrl = route('admin.core.unidade_produtiva.view', $row->id);

                return view('backend.components.form-actions.index', compact('editUrl', 'deleteUrl', 'viewUrl', 'row'));
            })
            ->filterColumn('produtores', function ($query, $keyword) {
                if ($keyword) {
                    $query->whereHas('produtores', function ($q) use ($keyword) {
                        $q->where('produtores.nome', 'like', '%' . $keyword . '%');
                    });
                }
            })
            ->rawColumns(['produtores'])
            ->make(true);
    }

    /**
     * Cadastro
     *
     * @param  ProdutorModel $produtor
     * @param  FormBuilder $formBuilder
     * @return void
     */
    public function create(ProdutorModel $produtor, FormBuilder $formBuilder)
    {

        // Definição do checklist de dados adicionais da UP
        if(config('app.checklist_dados_adicionais_unidade_produtiva')){
            $checklist_id = config('app.checklist_dados_adicionais_unidade_produtiva');
            $checklist = ChecklistModel::find($checklist_id);
        }
        // dd()
        $model['produtor_id'] = $produtor->id;
        if ($produtor->fl_reside_unidade_produtiva) {
            $model['cep'] = $produtor->cep;
            $model['endereco'] = $produtor->endereco;
            $model['bairro'] = $produtor->bairro;
            $model['subprefeitura'] = $produtor->subprefeitura;
        }

        $form = $formBuilder->create(UnidadeProdutivaForm::class, [
            'id' => 'form-builder',
            'method' => 'POST',
            'url' => route('admin.core.unidade_produtiva.store'),
            'class' => 'needs-validation',
            'novalidate' => true,
            'enctype' => 'multipart/form-data',
            'model' => $model,
            'data' => ['checklist' => $checklist, 'produtor' => $produtor],
        ]);

        $title = 'Criar Unidade Produtiva';

        return view('backend.core.unidade_produtiva.create_update', compact('form', 'title'));
    }

    /**
     * Cadastro - POST
     *
     * @param  Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $form = $this->form(UnidadeProdutivaForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = $request->all();

        try {
            $unidadeProdutiva = $this->repository->create($data);

            if(config('app.checklist_dados_adicionais_unidade_produtiva')){
                // Salvando dados das respostas do checklist. Para isso foi preciso longo caminho para
                // instanciar o ChecklistUnidadeProdutivaRepository
                $planoAcaoItem = new PlanoAcaoItemModel();
                $planoAcaoItemRepository = new PlanoAcaoItemRepository($planoAcaoItem);
                $planoAcao = new PlanoAcaoModel();
                $planoAcaoRepository = new PlanoAcaoRepository($planoAcao, $planoAcaoItemRepository);
                $checklistUnidadeProdutiva = ChecklistUnidadeProdutivaModel::where('unidade_produtiva_id', $unidadeProdutiva->id)->where('checklist_id', config('app.checklist_dados_adicionais_unidade_produtiva'))->where('produtor_id', NULL)->first();

                $data['status'] = "rascunho";

                if($checklistUnidadeProdutiva){
                    // checklist existe e só precisa ser atualizado
                    $checklistUnidadeProdutivaRepository = new ChecklistUnidadeProdutivaRepository($checklistUnidadeProdutiva, $planoAcaoRepository);
                    $checklistUnidadeProdutivaRepository->update($checklistUnidadeProdutiva, $data);
                } else {
                    // checklist a ser criado
                    $checklistUnidadeProdutiva = new ChecklistUnidadeProdutivaModel();
                    $checklistUnidadeProdutivaRepository = new ChecklistUnidadeProdutivaRepository($checklistUnidadeProdutiva, $planoAcaoRepository);
                    $data['checklist_id'] = config('app.checklist_dados_adicionais_unidade_produtiva');
                    $data['unidade_produtiva_id'] = $unidadeProdutiva->id;
                    $checklistUnidadeProdutivaRepository->create($data);
                }
            }


        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('validation.productive_unit_coverage_fails'))->withInput();
        }

        //Sync dos dados multiplos (relacionamento com outras tabelas)
        $unidadeProdutiva->canaisComercializacao()->sync(@$data['canaisComercializacao']);
        $unidadeProdutiva->destinacaoProducao()->sync(@$data['destinacaoProducao']);
        $unidadeProdutiva->riscosContaminacaoAgua()->sync(@$data['riscosContaminacaoAgua']);
        $unidadeProdutiva->tiposFonteAgua()->sync(@$data['tiposFonteAgua']);
        $unidadeProdutiva->solosCategoria()->sync(@$data['solosCategoria']);
        $unidadeProdutiva->certificacoes()->sync(@$data['certificacoes']);
        $unidadeProdutiva->pressaoSociais()->sync(@$data['pressaoSociais']);
        $unidadeProdutiva->residuoSolidos()->sync(@$data['residuoSolidos']);
        $unidadeProdutiva->residuoOrganicos()->sync(@$data['residuoOrganicos']);
        $unidadeProdutiva->formaProcessamento()->sync(@$data['formaProcessamento']);
        $unidadeProdutiva->esgotamentoSanitarios()->sync(@$data['esgotamentoSanitarios']);

        //Upload do arquivo "croqui", caso tenha sido passado
        if ($request->hasFile('croqui_propriedade')) {
            $this->repository->uploadCroqui($request->file('croqui_propriedade'), $unidadeProdutiva);
        }

        /*Custom Redirect*/
        $redirect = route('admin.core.unidade_produtiva.index');
        if (@$data['submit_action'] == 'edit_after') {
            $redirect = route('admin.core.unidade_produtiva.edit', [$unidadeProdutiva->id]);
        }
        //Redireciona para o dash caso tenha produtor na hora do cadastro inicial
        if (@$data['produtor_id'] && @$data['tipo_posse_id']) {
            $redirect = route('admin.core.produtor.dashboard', [$data['produtor_id']]);
        }

        if (@$data['custom-redirect']) {
            $redirect = route('admin.core.unidade_produtiva.edit', [$unidadeProdutiva->id, '#' . $data['custom-redirect']]);
        }
        /*End Custom Redirect*/

        return redirect($redirect)->withFlashSuccess('Unidade Produtiva cadastrada com sucesso!');
    }

    /**
     * Edição
     *
     * @param  FormBuilder $formBuilder
     * @param  ProdutorModel $produtor
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function edit(FormBuilder $formBuilder, ProdutorModel $produtor, UnidadeProdutivaModel $unidadeProdutiva)
    {
        $urlForm = @$produtor->id ?
            route('admin.core.novo_produtor_unidade_produtiva.unidade_produtiva_update', compact('produtor', 'unidadeProdutiva')) :
            route('admin.core.unidade_produtiva.update', compact('unidadeProdutiva'));


        // Definição do checklist de dados adicionais da UP
        if(config('app.checklist_dados_adicionais_unidade_produtiva')){
            $checklist_id = config('app.checklist_dados_adicionais_unidade_produtiva');
            $checklist = ChecklistModel::find($checklist_id);
            $unidProdutivaRespostas = ChecklistUnidadeProdutivaController::getRespostas($checklist, NULL, $unidadeProdutiva);
            // inclui dados do checklist no objeto da UP
            foreach($unidProdutivaRespostas as $k => $v){
                $unidadeProdutiva->$k = $v;
            }
            $model = $unidadeProdutiva;
        } else {
            $unidProdutivaRespostas = NULL;
            $checklist = NULL;
            $model = $unidadeProdutiva;
        }

        $produtores = $unidadeProdutiva->produtores();

        $form = $formBuilder->create(UnidadeProdutivaForm::class, [
            'id' => 'form-builder',
            'method' => 'PATCH',
            'url' => $urlForm,
            'class' => 'needs-validation',
            'novalidate' => true,
            'model' => $model,
            'data' => ['checklist' => $checklist, 'produtores' => $produtores],
            'enctype' => 'multipart/form-data'
        ]);

        $title = 'Editar Unidade Produtiva';

        //Iframe "Pessoas" (ver UnidadeProdutivaColaboradorTrait.php)
        $colaboradoresId = 'iframeColaboradores';
        $colaboradoresSrc = route('admin.core.unidade_produtiva.colaboradores.index', compact('unidadeProdutiva'));

        //Iframe "Infra-estrutura" (ver UnidadeProdutivaInstalacoesTrait.php)
        // $instalacoesId = 'iframeInstalacoes';
        // $instalacoesSrc = route('admin.core.unidade_produtiva.instalacoes.index', compact('unidadeProdutiva'));

        //Iframe "Culturas" (ver UnidadeProdutivaCulturasTrait.php)
        $culturasId = 'iframeCulturas';
        $culturasSrc = route('admin.core.unidade_produtiva.culturas.index', compact('unidadeProdutiva'));

        //Iframe "Infra e Ferramentas" (ver UnidadeProdutivaInfraFerramentasTrait.php)
        $infraFerramentasId = 'iframeInfraFerramentas';
        $infraFerramentasSrc = route('admin.core.unidade_produtiva.infra_ferramentas.index', compact('unidadeProdutiva'));

        // Iframe "Uso do Solo" (ver UnidadeProdutivaCaracterizacoesTrait.php)
        // $caracterizacoesId = 'iframeCaracterizacoes';
        // $caracterizacoesSrc = route('admin.core.unidade_produtiva.caracterizacoes.index', compact('unidadeProdutiva'));

        //Iframe "Arquivos" (ver UnidadeProdutivaArquivosTrait.php)
        $arquivosId = 'iframeArquivos';
        $arquivosSrc = route('admin.core.unidade_produtiva.arquivos.index', compact('unidadeProdutiva'));

        $produtorasId = 'iframeProdutora';
        $produtorasSrc = route('admin.core.unidade_produtiva.search-produtor', compact('unidadeProdutiva'));

        return view('backend.core.unidade_produtiva.create_update', compact('form', 'title', 'unidadeProdutiva', 'produtor', 'produtorasId', 'produtorasSrc', 'colaboradoresId', 'colaboradoresSrc', 'infraFerramentasId', 'infraFerramentasSrc', 'culturasId', 'culturasSrc', 'arquivosId', 'arquivosSrc'));
    }


    /**
     * Edição - POST
     *
     * @param  ProdutorModel $produtor
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @param  Request $request
     * @return void
     */
    public function update(ProdutorModel $produtor, UnidadeProdutivaModel $unidadeProdutiva, Request $request)
    {
        $form = $this->form(UnidadeProdutivaForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $data = $request->all();
        try {
            $unidadeProdutiva = $this->repository->update($unidadeProdutiva, $data);

            if(config('app.checklist_dados_adicionais_unidade_produtiva')){
                // Salvando dados das respostas do checklist. Para isso foi preciso longo caminho para
                // instanciar o ChecklistUnidadeProdutivaRepository
                $planoAcaoItem = new PlanoAcaoItemModel();
                $planoAcaoItemRepository = new PlanoAcaoItemRepository($planoAcaoItem);
                $planoAcao = new PlanoAcaoModel();
                $planoAcaoRepository = new PlanoAcaoRepository($planoAcao, $planoAcaoItemRepository);
                $checklistUnidadeProdutiva = ChecklistUnidadeProdutivaModel::where('unidade_produtiva_id', $unidadeProdutiva->id)->where('checklist_id', config('app.checklist_dados_adicionais_unidade_produtiva'))->where('produtor_id', NULL)->first();

                $data['status'] = "rascunho";

                if($checklistUnidadeProdutiva){
                    // checklist existe e só precisa ser atualizado
                    $checklistUnidadeProdutivaRepository = new ChecklistUnidadeProdutivaRepository($checklistUnidadeProdutiva, $planoAcaoRepository);
                    $checklistUnidadeProdutivaRepository->update($checklistUnidadeProdutiva, $data);
                } else {
                    // checklist a ser criado
                    $checklistUnidadeProdutiva = new ChecklistUnidadeProdutivaModel();
                    $checklistUnidadeProdutivaRepository = new ChecklistUnidadeProdutivaRepository($checklistUnidadeProdutiva, $planoAcaoRepository);
                    $checklistUnidadeProdutivaRepository->create($data);
                }
            }

        } catch (Exception $e) {
            return redirect()->back()->withErrors(__('validation.productive_unit_coverage_fails'))->withInput();
        }

        //Sync custom, porque relacionamentos "belongsToMany" o "SoftDelete" não funciona ... foi criado uma função p/ esse tratamento especifico.
        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->canaisComercializacaoWithTrashed(), $unidadeProdutiva->id, @$data['canaisComercializacao']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->destinacaoProducaoWithTrashed(), $unidadeProdutiva->id, @$data['destinacaoProducao']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->riscosContaminacaoAguaWithTrashed(), $unidadeProdutiva->id, @$data['riscosContaminacaoAgua']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->tiposFonteAguaWithTrashed(), $unidadeProdutiva->id, @$data['tiposFonteAgua']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->solosCategoriaWithTrashed(), $unidadeProdutiva->id, @$data['solosCategoria']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->certificacoesWithTrashed(), $unidadeProdutiva->id, @$data['certificacoes']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->pressaoSociaisWithTrashed(), $unidadeProdutiva->id, @$data['pressaoSociais']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->residuoSolidosWithTrashed(), $unidadeProdutiva->id, @$data['residuoSolidos']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->residuoOrganicosWithTrashed(), $unidadeProdutiva->id, @$data['residuoOrganicos']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->formaProcessamentoWithTrashed(), $unidadeProdutiva->id, @$data['formaProcessamento']);

        SoftDeleteHelper::syncSoftDelete($unidadeProdutiva->esgotamentoSanitariosWithTrashed(), $unidadeProdutiva->id, @$data['esgotamentoSanitarios']);

        //Upload do arquivo (croqui) caso exista
        if ($request->hasFile('croqui_propriedade')) {
            $this->repository->uploadCroqui($request->file('croqui_propriedade'), $unidadeProdutiva);
        }

        //Tratamento de redirect específico. Existem dois fluxos. a) Edição unidade produtiva b) Cadastro rápido de um produtor/unidade produtiva
        if (@$produtor->id) {
            return redirect()->route('admin.core.produtor.dashboard', ['produtor' => $produtor])->withFlashSuccess('Unidade Produtiva alterado com sucesso!');
        } else if (@$data['submit_action'] == 'edit_after') {
            return redirect(route('admin.core.unidade_produtiva.edit', [$unidadeProdutiva->id]))->withFlashSuccess('Unidade Produtiva alterada com sucesso!');
        } else {
            return redirect()->route('admin.core.unidade_produtiva.index')->withFlashSuccess('Unidade Produtiva alterada com sucesso!');
        }
    }

    /**
     * Remover unidade produtiva (regras UnidadeProdutivaPolicy)
     *
     * @param  UnidadeProdutivaModel $unidadeProdutiva
     * @return void
     */
    public function destroy(UnidadeProdutivaModel $unidadeProdutiva)
    {
        $this->repository->delete($unidadeProdutiva);

        return redirect()->route('admin.core.unidade_produtiva.index')->withFlashSuccess('Unidade produtiva deletada com sucesso');
    }

    /**
     * Listagem de produtores, p/ seleção.
     *
     * Utilizado no momento de vincular um produtor com uma unidade produtiva
     *
     * @return void
     */
    public function produtor()
    {
        $datatableUrl = route('admin.core.unidade_produtiva.datatableProdutor');
        return view('backend.core.unidade_produtiva.produtor', compact('datatableUrl'));
    }

    /**
     * API Datatable "produtor()"
     *
     * @return void
     */
    public function datatableProdutor()
    {
        return DataTables::of(ProdutorModel::query())
            ->addColumn('actions', function ($row) {
                $addUrl = route('admin.core.unidade_produtiva.create', ['produtor' => $row->id]);
                return view('backend.components.form-actions.index', compact('addUrl'));
            })
            ->make(true);
    }
}
