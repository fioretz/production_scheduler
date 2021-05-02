<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdineProduzioneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordine_produzione', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numeroordine')->unique();
            $table->float('quantita');
            $table->float('tempoproduzione');
            $table->date('datascadenza');
            $table->date('datafine');
            $table->string('stato');
            $table->unsignedBigInteger('prodotto_id');
            $table->foreign('prodotto_id')->references('id')->on('prodotto');
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
        Schema::dropIfExists('ordine_produzione');
    }
}
