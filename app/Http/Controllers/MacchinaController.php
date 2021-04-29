<?php

namespace App\Http\Controllers;

use App\Models\Macchina;
use App\Models\TipoMacchina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MacchinaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show() {
        $macchine = DB::table('macchina')
            ->leftJoin('tipo_macchina', 'macchina.tipomacchina_id', '=', 'tipo_macchina.id')
            ->select(
                'macchina.id',
                'macchina.codice AS macchina_codice',
                'macchina.descrizione AS macchina_descrizione',
                'macchina.tipomacchina_id',
                'tipo_macchina.codice AS tipomacchina_codice',
                'tipo_macchina.descrizione AS tipomacchina_descrizione'
            )
            ->get();

        $tipoMacchina = TipoMacchina::all();

        return view('macchina.macchina', [ 'macchine'=>$macchine, 'tipomacchina'=>$tipoMacchina ]);
    }
}
