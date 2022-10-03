<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Client; //model client

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'nom'=> 'Ca la Pepa',
            'tipus' => 'det',
            'email' => 'calapepa@gmail.com',
            'telefon' => '938887766',
            'nota' => 'Plaça Major, 25 de Vallfogona, Horari de 9.00 a 14.00',
        ]);

        Client::create([
            'nom'=> 'Pepet Distribucions',
            'tipus' => 'dis',
            'email' => 'pepetdistribucions@gmail.com',
            'telefon' => '931112233',
            'nota' => 'Carrer Major, 33 de Vallferrera, Horari de 8.00 a 17.00',
        ]);

        Client::create([
            'nom'=> 'Can Bundància ',
            'tipus' => 'det',
            'email' => 'canbundancia@gmail.com',
            'telefon' => '976665544',
            'nota' => 'Carrer Nou, km 2, 17856 Oix, Girona',
        ]);
    }
}
