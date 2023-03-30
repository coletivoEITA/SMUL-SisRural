<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ResiduoOrganicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = Carbon::now();

        \App\Models\Core\ResiduoOrganicoModel::insert([
            ['id' => 1, 'nome' => 'Compostagem', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 2, 'nome' => 'Coleta municipal', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 3, 'nome' => 'Alimentação animal', 'created_at' => $createdAt, 'updated_at' => $createdAt],
            ['id' => 4, 'nome' => 'Queima', 'created_at' => $createdAt, 'updated_at' => $createdAt],
        ]);
    }
}
