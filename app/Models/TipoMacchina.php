<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TipoMacchina extends Model
{
    use HasFactory;

    protected $table = 'tipo_macchina';

    protected $primaryKey = 'id';

    protected $fillable = ['codice', 'descrizione'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($tipoMacchina) {

            $macchina = DB::table('macchina')->where('tipomacchina_id', $tipoMacchina->id)->first();

            if (!empty($macchina)) {
                throw new \Exception(sprintf('Impossibile eliminare tipo macchina %s, tipo macchina assegnato ad una macchina attiva', $tipoMacchina->codice));
            }

            $prodotto = DB::table('prodotto')->where('tipomacchina_id', $tipoMacchina->id)->first();

            if (!empty($prodotto)) {
                throw new \Exception(sprintf('Impossibile eliminare tipo macchina %s, tipo macchina assegnato ad un prodotto', $tipoMacchina->codice));
            }
        });
    }
}
