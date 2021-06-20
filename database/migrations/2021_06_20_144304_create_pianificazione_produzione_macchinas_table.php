<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePianificazioneProduzioneMacchinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pianificazione_produzione_macchina', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('macchina_codice');
            $table->string('macchina_descrizione');
            $table->string('tipomacchina_codice');
            $table->string('tipomacchina_descrizione');
            $table->float('tempoutilizzo');
            $table->unsignedBigInteger('pianprodtesta_id');
            $table->foreign('pianprodtesta_id')->references('id')->on('pianificazione_produzione_testa');
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
        Schema::dropIfExists('pianificazione_produzione_macchina');
    }
}
