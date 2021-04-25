<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Macchina extends Model
{
    use HasFactory;

    protected $table = 'macchina';

    protected $primaryKey = 'id';

    protected $fillable = ['codice', 'descrizione', 'tipomacchina_id'];
}
