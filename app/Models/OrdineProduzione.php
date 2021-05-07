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

    public static function boot()
    {
        parent::boot();

        static::updating(function($ordineProduzione){
            if ($ordineProduzione->getOriginal('numeroordine') !== $ordineProduzione->getAttribute('numeroordine')) {
                throw new \Exception(sprintf('Impossibile modificare Numero Ordine'));
            }

            if ($ordineProduzione->stato == self::STATO_CHIUSO) {
                throw new \Exception(sprintf('Impossibile modificare Ordini di Produzione chiusi'));
            }

            if ($ordineProduzione->stato == self::STATO_IN_PRODUZIONE) {
                throw new \Exception(sprintf('Impossibile modificare Ordini di Produzione in fase di produzione'));
            }

        });

        static::deleting(function($ordineProduzione){

            if ($ordineProduzione->stato == self::STATO_CHIUSO) {
                throw new \Exception(sprintf('Impossibile eliminare Ordini di Produzione chiusi'));
            }

            if ($ordineProduzione->stato == self::STATO_IN_PRODUZIONE) {
                throw new \Exception(sprintf('Impossibile eliminare Ordini di Produzione in fase di produzione'));
            }

        });
    }

}
