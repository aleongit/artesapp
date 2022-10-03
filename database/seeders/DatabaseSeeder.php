<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//models
use App\Models\User;
use App\Models\Categoria;
use App\Models\Producte;
use App\Models\Client;
use App\Models\Comanda;
use App\Models\Comanda_item;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //User::factory(2)->create();

        //model factory categoria
        //Categoria::factory(6)->create();

        //Calling Additional Seeders
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CategoriaSeeder::class,
            ProducteSeeder::class,
            ClientSeeder::class,
            ComandaSeeder::class,
            ComandaItemSeeder::class,
        ]);
    }
}
