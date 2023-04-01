<?php

use App\Models\Core\CulturaModel;
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
        $pathname = "resources/xlsx/Culturas.xlsx";
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
            $cultura = new CulturaModel();
            $cultura->nome = $nome;
            $cultura->id = $id;
            $cultura->categoria = $categoria;
            $cultura->save();
        }
    }
}

