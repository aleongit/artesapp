<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comanda_items', function (Blueprint $table) {
            $table->id();
            //$table->bigInteger('id');
            $table->foreignId('comanda_id')->constrained('comandes');
            $table->foreignId('producte_id')->constrained('productes');
            $table->integer('unitats');
            $table->double('preu', 8,2);
            $table->double('import', 8,2);
            $table->timestamps();

            //2 primary keys
            //$table->primary(['id', 'comanda_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comanda_items');
    }
};
