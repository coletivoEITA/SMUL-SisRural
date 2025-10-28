<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Core\CadernoModel;
use App\Models\Core\ChecklistUnidadeProdutivaModel;
use App\Models\Core\PlanoAcaoModel;
use App\Models\Core\ProdutorModel;
use App\Models\Core\UnidadeProdutivaModel;
use App\Services\ReportService;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    private $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Retorno do dashboard principal do CMS
     *
     * @return void
     */
    public function index()
    {
        $totalCaderno = CadernoModel::count();
        $totalProdutor = ProdutorModel::count();
        $totalUnidProdutiva = UnidadeProdutivaModel::count();
        $totalFormulariosAplicados = ChecklistUnidadeProdutivaModel::count();

        $totalPlanoAcao = PlanoAcaoModel::individual()->count();
        $totalPlanoAcaoColetivo = PlanoAcaoModel::coletivo()->count();

        $action = route('admin.core.mapa.data');

        $viewFilter = $this->service->viewFilter($action, true, false, false);

        return view('backend.dashboard', compact('totalCaderno', 'totalProdutor', 'totalUnidProdutiva', 'totalFormulariosAplicados', 'totalPlanoAcao', 'totalPlanoAcaoColetivo', 'action', 'viewFilter'));
    }
}
