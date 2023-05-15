<?php

use App\Models\Core\InfraFerramentaModel;
use App\Models\Core\UnidadeProdutivaInfraFerramentaModel;
use App\Models\Core\UnidadeProdutivaModel;
use Illuminate\Database\Seeder;

class InfraFerramentaImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // script para migração de dados da pergunta infra e ferramentas #102

        $pathname = "resources/xlsx/migracao_ferramentas_infra.xlsx";
        $numberSheet = 0;
        $firstLine = 2;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathname);
        $sheet = $spreadsheet->getSheet($numberSheet);
        $highestRow = $sheet->getHighestRow();
        
        for($i = $firstLine; $i <= $highestRow; $i++){
            $uid = $sheet->getCell("A" . $i)->getValue();
            $infra_ferramenta_str = $sheet->getCell("E" . $i)->getValue();
            if( $infra_ferramenta_str ){
                $id_up = UnidadeProdutivaModel::where('uid', $uid)->first()->id;
                $up_infra_fer = new UnidadeProdutivaInfraFerramentaModel();
                $up_infra_fer->unidade_produtiva_id = $id_up;
                $infra_ferramenta_id = InfraFerramentaModel::where('nome', $infra_ferramenta_str)->first()->id;
                $up_infra_fer->infra_ferramenta_id = $infra_ferramenta_id;
                $up_infra_fer->quantidade = $sheet->getCell("F" . $i)->getValue();
                $up_infra_fer->situacao = $sheet->getCell("G" . $i)->getValue();
                $up_infra_fer->save();
            }
        }
    }
}

