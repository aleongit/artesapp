<?php

namespace App\Http\Controllers;

use App\Models\Producte;
use App\Models\Categoria;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProducteController extends Controller
{   
    /**
     * Llistar categories
     * 
     * @return \Illuminate\Http\Response
     */
    public function categories() {
        $categories = Categoria::all();
        return response()->json($categories, 200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //llistar productes
       //$productes = Producte::orderBy('created_at','asc')->paginate(5);

       //amb with 'categoria' retornem també la relació
       //en aquest cas per cada producte, retorna array de la categoria relacionada
       $productes = Producte::orderBy('created_at','asc')
        ->with('categoria')->paginate(5);

       return response()->json($productes, 200);
    }

    /**
     * get productes d'una categoria, sense paginació
     */
    public function productes_categoria(Categoria $categoria)
    {
       //echo("hola");
       //var_dump($categoria->id);
       
       $productes = Producte::where('categoria_id', $categoria->id)->orderBy('created_at','asc')
        ->with('categoria')->paginate(5);;

       return response()->json($productes, 200);
       
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
        //guardar nou producte
        
        //validació
        $request->validate([
            'categoria_id' => 'required|exists:categories,id',
            'nom' => 'required|string|min:3',
            'preu' => 'required|numeric|gt:0',
            'imatge' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        //nova instància
        $producte = new Producte();

        //de request a model
        $producte->categoria_id = $request->categoria_id;
        $producte->nom = $request->nom;
        $producte->preu = $request->preu;
        
        //img
        $path = $request->file('imatge')->store('productes_img');
        $producte->imatge = $path;

        //si producte guardat
        if ( $producte->save() ) {
            return response()->json($producte, 200);
        } else {

            //borrem nova imatge a store ja que hi ha hagut error
            Storage::delete($path);

            return response()->json($producte, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producte  $producte
     * @return \Illuminate\Http\Response
     */
    public function show(Producte $producte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producte  $producte
     * @return \Illuminate\Http\Response
     */
    public function edit(Producte $producte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producte  $producte
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producte $producte)
    {
        //update producte

        //validació sense imatge, pot no haver nova imatge
        $request->validate([
            'categoria_id' => 'required|exists:categories,id',
            'nom' => 'required|string|min:3',
            'preu' => 'required|numeric|gt:0',
        ]);
        
        //canviem
        $producte->categoria_id = $request->categoria_id;
        $producte->nom = $request->nom;
        $producte->preu = $request->preu;

        //si request té fitxer imatge
        if ($request->hasFile('imatge')) {

            //validació imatge (ja que hi ha imatge)
            $request->validate([
            'imatge' => 'image|mimes:jpg,jpeg,png',
            ]);

            //path vell
            $oldPath = $producte->imatge;

            //guardem imatge a store
            $path = $request->file('imatge')->store('productes_img');
            $producte->imatge = $path;

            //borrem imatge store path vell
            Storage::delete($oldPath);
        }

        //si producte guardat 
        if ( $producte->save() ) {
            //retorna el producte i la relació amb categoria
            return response()->json($producte->with('categoria')->find($producte->id), 200);
        } else {

            //borrem nova imatge a store ja que hi ha hagut error
            Storage::delete($path);

            return response()->json([
                'message' => '* FATAL ERROR * update :(',
                'status_code' => 500]
                , 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producte  $producte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producte $producte)
    {
        //eliminar producte

        //si producte eliminat
        if ( $producte->delete() ) {

            //borrar imatge storage
            Storage::delete($producte->imatge);

            return response()->json([
                'message' => 'Producte eliminat ok! :)',
                'status_code' => 200],
                 200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * delete :(',
                'status_code' => 500],
                 500);
        }
    }
}
