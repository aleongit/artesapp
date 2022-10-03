<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Categoria; //model categoria

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([
            'id'=> 1,
            'nom'=> 'Tomàquets',
        ]);

        Categoria::create([
            'id'=> 2,
            'nom'=> 'Pebrots',
        ]);

        Categoria::create([
            'id'=> 3,
            'nom'=> 'Patates',
        ]);
    }
}
