<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatainizioToPianificazioneProduzioneTesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pianificazione_produzione_testa', function (Blueprint $table) {
            $table->date('datainizio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pianificazione_produzione_testa', function (Blueprint $table) {
            $table->dropColumn('datainizio');
        });
    }
}
