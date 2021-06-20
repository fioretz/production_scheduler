<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PianificazioneProduzioneMacchina extends Model
{
    use HasFactory;

    protected $table = 'pianificazione_produzione_macchina';

    protected $primaryKey = 'id';

    protected $fillable = ['macchina_codice', 'macchina_descrizione', 'tipomacchina_codice', 'tipomacchina_descrizione', 'tempoutilizzo', 'pianprodtesta_id'];
}
