<?php

namespace App\Http\Controllers;

use App\Models\TipoMacchina;
use Illuminate\Http\Request;

class TipoMacchinaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show() {
        $tipoMaccina = TipoMacchina::paginate(request('perPage', 5));

        return view('tipomacchina', [ 'data'=>$tipoMaccina ]);
    }

    public function create() {
        return view('tipomacchinacreate');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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

            $request->session()->flash('status', 'Tipo Macchina aggiunto con successo');
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->errorInfo[2]);
            return redirect()->back();
        }

        return redirect()->route('tipomacchina.show');
    }

    /**
     * @param $tipoMacchinaId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($tipoMacchinaId) {
        $tipoMacchina = TipoMacchina::findOrFail($tipoMacchinaId);

        return view('tipomacchinaedit', [ 'tipomacchina'=>$tipoMacchina ]);
    }

    /**
     * @param $tipoMacchinaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($tipoMacchinaId) {
        request()->validate([
            'codice' => 'required|min:3',
            'descrizione' => 'required'
        ]);

        try {
            $tipoMacchina = TipoMacchina::findOrFail($tipoMacchinaId);
            $tipoMacchina->codice = request()->input('codice');
            $tipoMacchina->descrizione = request()->input('descrizione');

            $tipoMacchina->update();

            request()->session()->flash('status', 'Tipo Macchina modificato correttamente');
        } catch (\Exception $e) {
            request()->session()->flash('error', $e->errorInfo[2]);
            return redirect()->back();
        }

        return redirect()->route('tipomacchina.show');
    }

    /**
     * @param $tipoMacchinaId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($tipoMacchinaId) {
        try {
            $tipoMacchina = TipoMacchina::findOrFail($tipoMacchinaId);

            $tipoMacchina->delete();

            request()->session()->flash('status', 'Tipo Macchina eliminato correttamente');
        } catch (\Exception $e) {
            request()->session()->flash('error', $e->errorInfo[2]);
            return redirect()->back();
        }

        return redirect()->back();
    }
}
