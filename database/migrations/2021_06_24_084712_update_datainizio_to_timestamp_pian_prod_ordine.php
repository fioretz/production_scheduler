<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDatainizioToTimestampPianProdOrdine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pianificazione_produzione_ordine', function (Blueprint $table) {
            $table->dateTime('datafine')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pianificazione_produzione_ordine', function (Blueprint $table) {
            $table->date('datafine')->change();
        });
    }
}
