<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda_item extends Model
{
    use HasFactory;

        //indiquem taula bd
        protected $table = 'comanda_items';

        //relació many to one (belongs to)
        //la comanda_item pertany a una comanda
        public function comanda() {
            return $this->belongsTo(Comanda::class, 'comanda_id');
        }

        //relació many to one (belongs to)
        //la comanda_item s'associa a un producte
        public function producte() {
            return $this->belongsTo(Producte::class, 'producte_id');
        }

}
