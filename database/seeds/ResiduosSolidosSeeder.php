<?php

use App\Models\Core\ResiduoOrganicoModel;
use App\Models\Core\UnidadeProdutivaResiduoOrganicoModel;
use App\Models\Core\UnidadeProdutivaModel;
use Illuminate\Database\Seeder;

class ResiduosSolidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // script para migração de dados da pergunta de resíduos sólidos para orgânicos #102

        $pathname = "resources/xlsx/migracao_destinacao_residuos.xlsx";
        $numberSheet = 0;
        $firstLine = 2;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathname);
        $sheet = $spreadsheet->getSheet($numberSheet);
        $highestRow = $sheet->getHighestRow();
        
        for($i = $firstLine; $i <= $highestRow; $i++){
            $uid = $sheet->getCell("A" . $i)->getValue();
            $residuo_organico_str = $sheet->getCell("E" . $i)->getValue();
            if( $residuo_organico_str ){
                $id_up = UnidadeProdutivaModel::where('uid', $uid)->first()->id;
                $up_res = new UnidadeProdutivaResiduoOrganicoModel();
                $up_res->unidade_produtiva_id = $id_up;
                $residuo_organico_id = ResiduoOrganicoModel::where('nome', $residuo_organico_str)->first()->id;
                $up_res->residuo_organico_id = $residuo_organico_id;
                $up_res->save();
            }
        }
    }
}

