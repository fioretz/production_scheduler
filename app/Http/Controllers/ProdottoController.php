<?php

namespace App\Http\Controllers;

use App\Models\Prodotto;
use App\Models\TipoMacchina;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdottoController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show() {
        $prodotti = DB::table('prodotto')
            ->leftJoin('tipo_macchina', 'prodotto.tipomacchina_id', '=', 'tipo_macchina.id')
            ->select(
                'prodotto.id',
                'prodotto.codice AS prodotto_codice',
                'prodotto.descrizione AS prodotto_descrizione',
                'prodotto.tipomacchina_id',
                'prodotto.tempounitarioproduzione AS tempounitarioproduzione',
                'tipo_macchina.codice AS tipomacchina_codice',
                'tipo_macchina.descrizione AS tipomacchina_descrizione'
            )
            ->get();

        foreach ($prodotti->all() as $item) {
            $oreMinutiSecondi = $this->getOreMinutiSecondiFromSecondi($item->tempounitarioproduzione);
            $item->ore = $oreMinutiSecondi['ore'];
            $item->minuti = $oreMinutiSecondi['minuti'];
            $item->secondi = $oreMinutiSecondi['secondi'];
        }

        $tipoMacchina = TipoMacchina::all();

        return view('prodotto.prodotto', [ 'prodotti'=>$prodotti, 'tipomacchina'=>$tipoMacchina ]);
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

            $ore = intval($request['ore']);
            $minuti = intval($request['minuti']);
            $secondi = intval($request['secondi']);
            $tempoUnitarioProduzione = $secondi + ($minuti * 60) + ($ore * 3600);

            $prodotto = new Prodotto();
            $prodotto->codice = $codice;
            $prodotto->descrizione = $descrizione;
            $prodotto->tipomacchina_id = $tipoMacchinaId;
            $prodotto->tempounitarioproduzione = $tempoUnitarioProduzione;

            $prodotto->save();

            $request->session()->flash('status', 'Prodotto inserito correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->getMessage()]);
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
            $prodotto = Prodotto::findOrFail($id);
            $prodotto->codice = request()->input('codice');
            $prodotto->descrizione = request()->input('descrizione');
            $prodotto->tipomacchina_id = request()->input('tipomacchina_id');

            $ore = intval(request()->input('ore'));
            $minuti = intval(request()->input('minuti'));
            $secondi = intval(request()->input('secondi'));
            $tempoUnitarioProduzione = $secondi + ($minuti * 60) + ($ore * 3600);
            $prodotto->tempounitarioproduzione = $tempoUnitarioProduzione;

            $prodotto->update();

            request()->session()->flash('status', 'Prodotto modificato correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        } catch (\Exception $e) {
            return new JsonResponse(['errors' => $e->getMessage()]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $prodottoId
     * @return JsonResponse
     */
    public function delete($prodottoId) {
        try {
            $prodotto = Prodotto::findOrFail($prodottoId);

            $prodotto->delete();

            request()->session()->flash('status', 'Prodotto eliminato correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        } catch (\Exception $e) {
            request()->session()->flash('deleteError', $e->getMessage());
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getProdottoById($id) {
        $prodotto = Prodotto::find($id);

        $oreMinutiSecondi = $this->getOreMinutiSecondiFromSecondi($prodotto->getAttribute('tempounitarioproduzione'));
        $prodotto->ore = $oreMinutiSecondi['ore'];
        $prodotto->minuti = $oreMinutiSecondi['minuti'];
        $prodotto->secondi = $oreMinutiSecondi['secondi'];

        return response()->json($prodotto);
    }

    /**
     * @param $tempo
     * @return array
     */
    protected function getOreMinutiSecondiFromSecondi($tempo)
    {
        $ore = floor($tempo / 3600);
        $minuti = floor(($tempo / 60) % 60);
        $secondi = $tempo % 60;

        $oreMinutiSecondi = [
            'ore' => $ore,
            'minuti' => $minuti,
            'secondi' => $secondi
        ];

        return $oreMinutiSecondi;
    }
}
