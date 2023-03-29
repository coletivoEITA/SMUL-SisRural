<?php

use App\Models\Core\CnaeProduto;
use Illuminate\Database\Seeder;

class CnaeProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pathname = "resources/xlsx/EstruturaProdlistAgroPesca2018.xls";
        $numberSheet = 0;
        $firstLine = 5;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        $spreadsheet = $reader->load($pathname);
        $sheet = $spreadsheet->getSheet($numberSheet);
        $highestRow = $sheet->getHighestRow();
        
        for($i = $firstLine; $i <= $highestRow; $i++){
            $codigo = $sheet->getCell("A" . $i)->getValue();
            // Verifica se o código está no formato do produto: 0000.0000            
            preg_match('/[0-9]{4}\.[0-9]{4}/', $codigo, $output_array);
            if(count($output_array) != 1){
                continue;
            }
            $classe = explode(".", $output_array[0])[0];
            $prodlist = explode(".", $output_array[0])[1];
            $nome = $sheet->getCell("B" . $i)->getValue();
            $unidade_de_medida = $sheet->getCell("C" . $i)->getValue();
            $nome_cientifico = $sheet->getCell("F" . $i)->getValue();
            
            $produto = new CnaeProduto();
            $produto->nome = $nome;
            $produto->nome_cientifico = $nome_cientifico;
            $produto->unidade_de_medida = $unidade_de_medida;
            $produto->codigo_CNAE_classe = $classe;
            $produto->codigo_CNAE_prodlist = $prodlist;
            $produto->save();
        }
    }
}

