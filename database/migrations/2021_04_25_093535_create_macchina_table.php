<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMacchinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('macchina', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codice')->unique();
            $table->string('descrizione');
            $table->unsignedBigInteger('tipomacchina_id');
            $table->foreign('tipomacchina_id')->references('id')->on('tipo_macchina');
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
        Schema::dropIfExists('macchina');
    }
}
