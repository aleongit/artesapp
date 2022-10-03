<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producte extends Model
{
    use HasFactory;

    //indiquem taula bd
    protected $table = 'productes';

    //relació many to one (belongs to)
    public function categoria() {
        //la categoria del producte
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

        //relació one to many
    //un producte pot estar a moltes línies comanda
    public function comanda_items() {
        //return $this->hasMany($this->comment)->orderBy('id','desc');
        return $this->hasMany(Comanda_item::class)->orderBy('id','desc');
    }
}
