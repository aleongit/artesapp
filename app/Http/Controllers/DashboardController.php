<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{   
    /**
     * retorna comptadors
     */
    public function comptadors() {
        
        $categories = DB::table('categories')->count();
        //echo($categories);
        $productes = DB::table('productes')->count();
        $clients = DB::table('clients')->count();
        $comandes = DB::table('comandes')->count();
        
        return response()->json([
            $categories,
            $productes,
            $clients,
            $comandes
            ]
            ,200);

    }
}
