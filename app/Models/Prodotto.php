<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotto';

    protected $primaryKey = 'id';

    protected $fillable = ['codice', 'descrizione', 'tempounitarioproduzione', 'tipomacchina_id'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($prodotto) {

            if ($prodotto->tempounitarioproduzione <= 0) {
                throw new \Exception('Il tempo unitario di produzione deve essere maggiore di 0');
            }

        });
    }
}
