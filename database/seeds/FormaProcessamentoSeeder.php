<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FormaProcessamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = Carbon::now();

        \App\Models\Core\FormaProcessamentoModel::insert([
            ['id' => 1, 'nome' => 'Compotas, geléias, pastas e conservas', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 2, 'nome' => 'Desidratados e moídos', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 3, 'nome' => 'Minimamente processados (lava, corta e embala)', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 4, 'nome' => 'Congelados', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 5, 'nome' => 'Panificados', 'created_at' => $createdAt, 'updated_at' => $createdAt],
        ]);
    }
}
