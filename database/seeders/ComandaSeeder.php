<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

use App\Models\Comanda;

class ComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comanda::create([
            'client_id' => 1,
            'data_expedicio' => Carbon::now()->addDays(7),
            'import' => 19.85,
        ]);
    }
}
