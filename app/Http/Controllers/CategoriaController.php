<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule; //rules

class CategoriaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //llistar categories
       $categories = Categoria::orderBy('created_at','asc')->paginate(5);
       return response()->json($categories, 200);
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
        //guardar nova categoria

        //dd($request->all());
        //echo($request->nom);
        //echo('hola');

        $request->validate([
            'nom' => 'required|string|min:3',
            //'codi' => 'required|unique:categories|numeric|max:1',
        ]);

        //echo($request->nom);
        $cat = new Categoria();

        //de request a model
        $cat->nom = $request->nom;

        //si cat guardada
        if ( $cat->save() ) {
            return response()->json($cat, 200);
        } else {
            return response()->json($cat, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //update categoria
        //dd($request->all(), $categoria);
        //echo($request->nom);

        $request->validate([
            'nom' => 'required|string|min:3',
        ]);
        
        //canviem nom cat pel nom rebut de request
        $categoria->nom = $request->nom;

        //si cat guardada 
        if ( $categoria->save() ) {
            return response()->json($categoria, 200);
        } else {
            return response()->json([
                'message' => '* FATAL ERROR * update :(',
                'status_code' => 500]
                , 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //eliminar categoria
        //compte, paràmetre url ha de ser el mateix nom que model
        //compte català plural/singular

        //dd($categoria);
        //si cat eliminada
        if ( $categoria->delete() ) {
            return response()->json([
                'message' => 'Categoria eliminada ok! :)',
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
