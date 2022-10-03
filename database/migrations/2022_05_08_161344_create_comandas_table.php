<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Carbon\Carbon; //carbon

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->enum('estat', ['pendent', 'completada', 'descartada'])->default('pendent');
            $table->date('data_comanda')->default(Carbon::now());
            $table->date('data_expedicio')->default(Carbon::now());
            $table->double('import', 8,2)->default(0);
            $table->string('nota')->default('nota comanda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comandas');
    }
};
