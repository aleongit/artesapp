<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; //per transaccions i rollback en cas error

use Carbon\Carbon; //carbon

use App\Models\Comanda;
use App\Models\Comanda_item;
use App\Models\Client;
use App\Models\Categoria;
use App\Models\Producte;

class ComandaController extends Controller
{   
    /**
     * Retorna estats comanda = enum tipus base de dades
     */
    public function estats() 
    {
        $tipus = ['pendent','completada','descartada'];
        return response()->json($tipus, 200);
    }

    /**
     * obtenir tots els clients per treballar amb comandes
     */
    public function clients() {
        $clients = Client::all();
        return response()->json($clients, 200);
    }

        /**
     * Get categories i els seus productes
     * 
     * @return \Illuminate\Http\Response
     */
    public function categories_productes() {
        $categories = Categoria::with('productes')->get();
        return response()->json($categories, 200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //llistar comandes
        //$comandes = Comanda::orderBy('data_expedicio','desc')
        //    ->with('client')->paginate(5);
        
        //retorn de comandes amb el seu detall, i també les dades del client id 
        //retorn de 2 relacions amb 'with':
        // . comanda item que pertany a comanda + que pertany al producte + que pertany a categoria
        // . client que pertany a comanda
        $comandes = Comanda::orderBy('data_expedicio','asc')
        ->with('comanda_items.producte.categoria','client')->paginate(100);
        
        return response()->json($comandes, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //var_dump($request->items);
        //var_dump($request->client_id);
              
        //tot i estar controlat al front, 
        //validem comanda per la mala gent que sap js
        //SEMPRE CAL VALIDAR AL BACK END!
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'data_expedicio' => 'required|date|date_format:Y-m-d|after:yesterday',
            'import' => 'required|numeric|gt:0',
            'nota' => 'string|nullable',
            //validació items array d'objectes
            'items.*.producte_id' => 'required|exists:productes,id',
            'items.*.unitats' => 'required|numeric|gt:0',
            'items.*.import' => 'required|numeric|gt:0',
        ]);

        //TODO_ estaria bé validar el preu, i que sigui el mateix que la bd
        //no he trobat com fer-ho
        
        //nova instància
        $comanda = new Comanda();

        //de request a model
        $comanda->client_id = $request->client_id;
        $comanda->data_expedicio = $request->data_expedicio;
        $comanda->import = $request->import;
        $comanda->nota = $request->nota;

        //inicialitzem altres camps via laravel
        $comanda->estat = 'pendent';
        $comanda->data_comanda = Carbon::now();

        //var_dump($comanda);

        $ok = false;
        //provem transacció, si error fa rollback
        DB::beginTransaction();
        try {
            //guardem comanda
            $comanda->save();

            //guardem items comanda
    
            //var_dump($request->items);
            foreach ($request->items as $index=>$item) {
                //var_dump($item);
                /*
                foreach($item as $k => $v) {
                    echo("$k => $v\n");
                }
                */
                $comanda_item = new Comanda_item();
                //$comanda_item->id = $index + 1;
                $comanda_item->comanda_id = $comanda->id;
                $comanda_item->producte_id = $item['producte_id'];
                $comanda_item->unitats = $item['unitats'];
                $comanda_item->preu = $item['preu'];
                $comanda_item->import = $item['import'];
                $comanda_item->save();
            }

            //commit!
            DB::commit();

            //tot ok
            $ok = true;

        } catch (Exception $e) {
            DB::rollback();
        }

        //si tot ok
        if ( $ok ) {
            return response()->json($comanda, 200);
        } else {
            return response()->json($comanda, 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function show(Comanda $comanda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function edit(Comanda $comanda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comanda $comanda)
    {
        //var_dump($request->items);
        //var_dump($request->client_id);
              
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'data_expedicio' => 'required|date|date_format:Y-m-d|after:yesterday',
            'import' => 'required|numeric|gt:0',
            'nota' => 'string|nullable',
            'estat' => 'required|in:pendent,completada,descartada', //enums canvia a partir de php 8.1
            //validació items array d'objectes
            'items.*.producte_id' => 'required|exists:productes,id',
            'items.*.unitats' => 'required|numeric|gt:0',
            'items.*.import' => 'required|numeric|gt:0',
        ]);
        
        //canviem
        $comanda->client_id = $request->client_id;
        $comanda->data_expedicio = $request->data_expedicio;
        $comanda->import = $request->import;
        $comanda->nota = $request->nota;
        
        $comanda->estat = $request->estat;
        //$comanda->data_comanda = Carbon::now();

        //var_dump($comanda);

        $ok = false;
        //provem transacció, si error fa rollback
        DB::beginTransaction();
        try {
            //guardem comanda
            $comanda->save();

            //borrar els items comanda actual
            $items_vells = Comanda_item::where('comanda_id', $comanda->id)->delete();
            //echo($items_vells);

            //var_dump($request->items);
            //tornar a generar els items comanda
            foreach ($request->items as $index=>$item) {
                
                $comanda_item = new Comanda_item();
                //$comanda_item->id = $index + 1;
                $comanda_item->comanda_id = $comanda->id;
                $comanda_item->producte_id = $item['producte_id'];
                $comanda_item->unitats = $item['unitats'];
                $comanda_item->preu = $item['preu'];
                $comanda_item->import = $item['import'];
                $comanda_item->save();
            }

            //commit!
            DB::commit();

            //tot ok
            $ok = true;

        } catch (Exception $e) {
            DB::rollback();
        }

        //si tot ok
        if ( $ok ) {
            return response()->json($comanda, 200);
        } else {
            return response()->json($comanda, 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comanda  $comanda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comanda $comanda)
    {
        //
    }
    
    /**
     * Canvia estat comanda
     */
    public function set_estat(Request $request, Comanda $comanda)
    {   
        //echo('set_estat');
        //var_dump($request->estat);
        //var_dump($comanda);
        
        $request->validate([
            'estat' => 'required|in:completada,descartada', //enums canvia a partir de php 8.1
        ]);
    
        //canviem
        $comanda->estat = $request->estat;

        //si client update
        if ($comanda->save()) {
            return response()->json($comanda, 200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * update :(',
                'status_code' => 500]
                , 500);
        }
        
    }
}
