<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PianificazioneProduzioneTesta extends Model
{
    use HasFactory;

    protected $table = 'pianificazione_produzione_testa';

    protected $primaryKey = 'id';

    protected $fillable = ['nome', 'descrizione', 'datacreazione'];
}
