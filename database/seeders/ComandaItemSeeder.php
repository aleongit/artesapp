<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Comanda_item;

class ComandaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comanda_item::create([
            //'id'=> 1,
            'comanda_id' => 1,
            'producte_id' => 1,
            'unitats' => 2,
            'preu' => 5.25,
            'import' => 5.25 * 2,
        ]);

        Comanda_item::create([
            //'id'=> 2,
            'comanda_id' => 1,
            'producte_id' => 10,
            'unitats' => 10,
            'preu' => 0.26,
            'import' => 0.26 * 10,
        ]);

        Comanda_item::create([
            //'id'=> 3,
            'comanda_id' => 1,
            'producte_id' => 15,
            'unitats' => 3,
            'preu' => 2.25,
            'import' => 2.25 * 3,
        ]);
    }
}
