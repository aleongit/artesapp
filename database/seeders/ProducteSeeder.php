<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Producte; //model producte

class ProducteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        //tomàquets
        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Artesapp',
            'preu' => 5.25,
            'imatge' => 'img/TOMATO.png'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Madur',
            'preu' => 0.16,
            'imatge' => 'img/TOMAQUET_MADUR.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Pera',
            'preu' => 0.27,
            'imatge' => 'img/TOMAQUET_PERA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Verd',
            'preu' => 0.35,
            'imatge' => 'img/TOMAQUET_VERD.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Rosa',
            'preu' => 0.51,
            'imatge' => 'img/TOMAQUET_ROSA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Montserrat',
            'preu' => 0.65,
            'imatge' => 'img/TOMAQUET_MONTSERRAT.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Ramell',
            'preu' => 0.54,
            'imatge' => 'img/TOMAQUET_RAMELL.jpg'
        ]);

        Producte::create([
            'categoria_id' => 1,
            'nom'=> 'Tomàquet Sucar',
            'preu' => 1.89,
            'imatge' => 'img/TOMAQUET_SUCAR.jpg'
        ]);

        //pebrots
        Producte::create([
            'categoria_id' => 2,
            'nom'=> 'Pebrot Vermell',
            'preu' => 0.58,
            'imatge' => 'img/PEBROT_VERMELL.jpg'
        ]);

        Producte::create([
            'categoria_id' => 2,
            'nom'=> 'Pebrot Verd',
            'preu' => 0.26,
            'imatge' => 'img/PEBROT_VERD.jpg'
        ]);

        Producte::create([
            'categoria_id' => 2,
            'nom'=> 'Pebrot Padró',
            'preu' => 1.19,
            'imatge' => 'img/PEBROT_PADRO.jpg'
        ]);

        Producte::create([
            'categoria_id' => 2,
            'nom'=> 'Pebrot Colors',
            'preu' => 1.59,
            'imatge' => 'img/PEBROT_COLORS.jpg'
        ]);

        Producte::create([
            'categoria_id' => 2,
            'nom'=> 'Pebrot Picant',
            'preu' => 1.29,
            'imatge' => 'img/PEBROT_PICANT.jpg'
        ]);

        //patates
        Producte::create([
            'categoria_id' => 3,
            'nom'=> 'Patata Nova',
            'preu' => 3.25,
            'imatge' => 'img/PATATA_NOVA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 3,
            'nom'=> 'Patata Vella',
            'preu' => 2.25,
            'imatge' => 'img/PATATA_VELLA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 3,
            'nom'=> 'Patata Blanca',
            'preu' => 3.89,
            'imatge' => 'img/PATATA_BLANCA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 3,
            'nom'=> 'Patata Muntanya',
            'preu' => 3.99,
            'imatge' => 'img/PATATA_MUNTANYA.jpg'
        ]);

        Producte::create([
            'categoria_id' => 3,
            'nom'=> 'Patata Vermella',
            'preu' => 3.49,
            'imatge' => 'img/PATATA_VERMELLA.jpg'
        ]);


    }
}

/*
            $table->id();
            $table->foreignId('categoria_id')->constrained('categories');
            $table->string('nom');
            $table->double('preu');
            $table->text('imatge');
            $table->timestamps();
*/