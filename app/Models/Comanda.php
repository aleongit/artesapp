<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    use HasFactory;

        //indiquem taula bd
        protected $table = 'comandes';

        //relació many to one (belongs to)
        //la comanda pertany a un client
        public function client() {
            return $this->belongsTo(Client::class, 'client_id');
        }

        //relació one to many
        //una comanda té línies comanda
        public function comanda_items() {
            //return $this->hasMany($this->comment)->orderBy('id','desc');
            return $this->hasMany(Comanda_item::class)->orderBy('id','asc');
        }

}
