<?php

use App\Models\Core\InfraFerramentaModel;
use Illuminate\Database\Seeder;

class InfraFerramentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pathname = "resources/xlsx/InfraFerramentas.xlsx";
        $numberSheet = 0;
        $firstLine = 2;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($pathname);
        $sheet = $spreadsheet->getSheet($numberSheet);
        $highestRow = $sheet->getHighestRow();
        
        for($i = $firstLine; $i <= $highestRow; $i++){
            $id = $sheet->getCell("A" . $i)->getValue();
            $nome = $sheet->getCell("B" . $i)->getValue();
            $categoria = $sheet->getCell("C" . $i)->getValue();            
            $infraFerramenta = new InfraFerramentaModel();
            $infraFerramenta->nome = $nome;
            $infraFerramenta->id = $id;
            $infraFerramenta->categoria = $categoria;
            $infraFerramenta->save();
        }
    }
}

