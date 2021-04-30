<?php

namespace App\Http\Controllers;

use App\Models\TipoMacchina;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoMacchinaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show() {
        $tipoMacchina = TipoMacchina::all();

        return view('tipomacchina.tipomacchina', [ 'data'=>$tipoMacchina ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $request->validate([
            'codice' => 'required|min:3',
            'descrizione' => 'required'
        ]);

        try {
            $codice = $request['codice'];
            $descrizione = $request['descrizione'];

            $tipoMacchina = new TipoMacchina();

            $tipoMacchina->codice = $codice;
            $tipoMacchina->descrizione = $descrizione;

            $tipoMacchina->save();

            request()->session()->flash('status', 'Tipo Macchina inserito correttamente');
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
            'descrizione' => 'required'
        ]);

        try {
            $id = request()->input('id');
            $tipoMacchina = TipoMacchina::findOrFail($id);
            $tipoMacchina->codice = request()->input('codice');
            $tipoMacchina->descrizione = request()->input('descrizione');

            $tipoMacchina->update();
            request()->session()->flash('status', 'Tipo Macchina modificato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $tipoMacchinaId
     * @return JsonResponse
     */
    public function delete($tipoMacchinaId) {
        try {
            $tipoMacchina = TipoMacchina::findOrFail($tipoMacchinaId);

            $tipoMacchina->delete();

            request()->session()->flash('status', 'Tipo Macchina eliminato correttamente');
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getTipoMacchinaById($id) {
        $tipoMacchina = TipoMacchina::find($id);
        return response()->json($tipoMacchina);
    }
}
