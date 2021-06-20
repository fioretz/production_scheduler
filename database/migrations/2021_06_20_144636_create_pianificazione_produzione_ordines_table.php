<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePianificazioneProduzioneOrdinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pianificazione_produzione_ordine', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numeroordine');
            $table->string('prodotto_codice');
            $table->string('prodotto_descrizione');
            $table->float('quantita');
            $table->date('datascadenza');
            $table->date('datainizio');
            $table->date('datafine');
            $table->unsignedBigInteger('pianprodmacchina_id');
            $table->foreign('pianprodmacchina_id')->references('id')->on('pianificazione_produzione_macchina');
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
        Schema::dropIfExists('pianificazione_produzione_ordines');
    }
}
