<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PianificazioneProduzioneOrdine extends Model
{
    use HasFactory;

    protected $table = 'pianificazione_produzione_ordine';

    protected $primaryKey = 'id';

    protected $fillable = ['numeroordine', 'prodotto_codice', 'prodotto_descrizione', 'quantita', 'datascadenza', 'datainizio', 'datafine', 'pianprodmacchina_id', 'sequenza'];
}
