<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

        //indiquem taula bd
        protected $table = 'categories';

        //relació one to many
        //mètode que retorna array amb els productes de la categoria
        public function productes() {
            //return $this->hasMany($this->comment)->orderBy('id','desc');
            return $this->hasMany(Producte::class)->orderBy('id','desc');
    }
}
