<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{   
    /**
     * Retorna tipus clients = enum tipus base de dades
     */
    public function tipus() 
    {
        $tipus = ['dis','det','con'];
        return response()->json($tipus, 200);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //llistar clients
        $clients = Client::orderBy('created_at','asc')->paginate(5);
        return response()->json($clients, 200);
        
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
        //echo($request->nota);

        //validació
        $request->validate([
            'nom' => 'required|string|min:3',
            'tipus' => 'required|in:dis,det,con', //enums canvia a partir de php 8.1
            'email' => 'required|string|email|unique:clients',
            'telefon' => 'required|numeric|digits:9|unique:clients',
        ]);

        //nova instància
        $client = new Client();

        //de request a model
        $client->nom = $request->nom;
        $client->tipus = $request->tipus;
        $client->email = $request->email;
        $client->telefon = $request->telefon;
        
        //així crea nota per defecte
        if ($request->nota) {
        $client->nota = $request->nota;
        }
        
        //si client guardat
        if ( $client->save() ) {
            return response()->json($client, 200);
        } else {
            return response()->json($client, 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //update client
        //echo($request->nom);
        //echo($client->id);

        //validació
        
        $request->validate([
            'nom' => 'required|string|min:3',
            'tipus' => 'required|in:dis,det,con', //enums canvia a partir de php 8.1
            'email' => ['required', 'string', 'email', 
                Rule::unique('clients')->ignore($client->id)],
            'telefon' => ['required', 'numeric', 'digits:9', 
                Rule::unique('clients')->ignore($client->id)],
        ]);
        
        
        //canviem
        $client->nom = $request->nom;
        $client->tipus = $request->tipus;
        $client->email = $request->email;
        $client->telefon = $request->telefon;
        $client->nota = $request->nota;
            
        //si client update
        if ($client->save()) {
                return response()->json($client, 200);
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //eliminar client

        //si client eliminat
        if ( $client->delete() ) {
            return response()->json([
                'message' => 'Client eliminat ok! :)',
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
