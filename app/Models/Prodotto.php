<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        static::updating(function($prodotto) {

            if ($prodotto->getOriginal('tempounitarioproduzione') !== $prodotto->getAttribute('tempounitarioproduzione')) {
                $ordineProduzione = DB::table('ordine_produzione')
                    ->where('prodotto_id', $prodotto->id)
                    ->where('stato', OrdineProduzione::STATO_IN_PRODUZIONE)
                    ->first();

                if (!empty($ordineProduzione)) {
                    throw new \Exception(sprintf('Impossibile modificare il tempo di produzione del prodotto %s, prodotto assegnato ad un ordine di produzione in fase di produzione', $prodotto->codice));
                }
            }

        });
    }
}
