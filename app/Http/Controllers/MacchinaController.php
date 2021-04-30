<?php

namespace App\Http\Controllers;

use App\Models\Macchina;
use App\Models\TipoMacchina;
use Illuminate\Http\JsonResponse;
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $request->validate([
            'codice' => 'required|min:3',
            'descrizione' => 'required',
            'tipomacchina_id' => 'required|exists:tipo_macchina,id'
        ]);

        try {
            $codice = $request['codice'];
            $descrizione = $request['descrizione'];
            $tipoMacchinaId = $request['tipomacchina_id'];

            $macchina = new Macchina();

            $macchina->codice = $codice;
            $macchina->descrizione = $descrizione;
            $macchina->tipomacchina_id = $tipoMacchinaId;

            $macchina->save();

            request()->session()->flash('status', 'Macchina inserita correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {
        request()->validate([
            'codice' => 'required|min:3',
            'descrizione' => 'required',
            'tipomacchina_id' => 'required|exists:tipo_macchina,id'
        ]);

        try {
            $id = request()->input('id');
            $macchina = Macchina::findOrFail($id);
            $macchina->codice = request()->input('codice');
            $macchina->descrizione = request()->input('descrizione');
            $macchina->tipomacchina_id = intval(request()->input('tipomacchina_id'));

            $macchina->update();

            request()->session()->flash('status', 'Macchina modificata correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $macchinaId
     * @return JsonResponse
     */
    public function delete($macchinaId) {
        try {
            $macchina = Macchina::findOrFail($macchinaId);

            $macchina->delete();

            request()->session()->flash('status', 'Macchina eliminata correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getMacchinaById($id) {
        $macchina = Macchina::find($id);

        $tipoMacchina = TipoMacchina::find($macchina->tipomacchina_id);

        return new JsonResponse(['macchina' => $macchina, 'tipomacchinaselected' => $tipoMacchina]);
    }
}
