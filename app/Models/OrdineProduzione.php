<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdineProduzione extends Model
{
    use HasFactory;

    const STATO_APERTO = 'A';
    const STATO_IN_PRODUZIONE = 'P';
    const STATO_CHIUSO = 'C';

    protected $table = 'ordine_produzione';

    protected $primaryKey = 'id';

    protected $fillable = ['numeroordine', 'quantita', 'datascadenza', 'prodotto_id', 'tempoproduzione', 'datafine', 'stato'];
}
