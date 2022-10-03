<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    //indiquem taula bd
    protected $table = 'clients';

    //relació one to many
    //un client té comandes
    public function comandes() {
        //return $this->hasMany($this->comment)->orderBy('id','desc');
        return $this->hasMany(Comanda::class)->orderBy('id','desc');
}

}
