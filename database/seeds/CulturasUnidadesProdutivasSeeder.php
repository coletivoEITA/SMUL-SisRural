<?php

use App\Models\Core\CulturaModel;
use App\Models\Core\UnidadeProdutivaCulturaModel;
use App\Models\Core\UnidadeProdutivaModel;
use Illuminate\Database\Seeder;

class CulturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pathname = "resources/xlsx/CulturasUnidadesProdutivas.xlsx";
        $numberSheet = 0;
        $firstLine = 2;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathname);
        $sheet = $spreadsheet->getSheet($numberSheet);
        $highestRow = $sheet->getHighestRow();
        
        for($i = $firstLine; $i <= $highestRow; $i++){
            $id_up = $sheet->getCell("A" . $i)->getValue();
            $cultura = $sheet->getCell("D" . $i)->getValue();
            if( ! $cultura ){
                continue;
            }
            if ($cultura_obj = CulturaModel::where('nome', $cultura)->first() ){
                $cultura_id = $cultura_obj->id;
            } else {                
                echo "Erro na cultura: " . $i;
                continue;
            }
            if( $up_obj = UnidadeProdutivaModel::where('uid', $id_up)->first() ){
                $up_id = $up_obj->id;
            } else {
                echo "Erro na up: " . $i;
                continue;
            }
            
            $cultura_up = new UnidadeProdutivaCulturaModel();            
            $cultura_up->cultura_id = $cultura_id;
            $cultura_up->unidade_produtiva_id = $up_id;
            $observacao = $sheet->getCell("F" . $i)->getValue();
            $cultura_up->observacao = $observacao;
            // $cultura_up->quantidade =             
            $cultura_up->save();

            // Outros usos
            if( $outros_usos = $sheet->getCell("G" . $i)->getValue() ){
                $up_obj->outros_usos_descricao = $up_obj->outros_usos_descricao . " " . $outros_usos;
                $up_obj->save();
            }
            

        }
    }
}

