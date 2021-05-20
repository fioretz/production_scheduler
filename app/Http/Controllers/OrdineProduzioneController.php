<?php

namespace App\Http\Controllers;

use App\Models\OrdineProduzione;
use App\Models\Prodotto;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdineProduzioneController extends Controller
{
    public function show() {
        $ordiniProduzione = DB::table('ordine_produzione')
            ->leftJoin('prodotto', 'ordine_produzione.prodotto_id', '=', 'prodotto.id')
            ->select(
                'ordine_produzione.id',
                'ordine_produzione.numeroordine AS numeroordine',
                'ordine_produzione.quantita AS ordineproduzione_quantita',
                'ordine_produzione.tempoproduzione AS tempoproduzione',
                'ordine_produzione.datascadenza AS datascadenza',
                'ordine_produzione.datafine AS datafine',
                'ordine_produzione.stato AS stato',
                'ordine_produzione.prodotto_id',
                'prodotto.codice AS prodotto_codice',
                'prodotto.descrizione AS prodotto_descrizione',
            )
            ->where('stato', '!=', OrdineProduzione::STATO_CHIUSO)
            ->orderBy('datascadenza', 'asc')
            ->get();

        foreach ($ordiniProduzione->all() as $ordine) {
            $oreMinutiSecondi = $this->getOreMinutiSecondiFromSecondi($ordine->tempoproduzione);
            $ordine->ore = $oreMinutiSecondi['ore'];
            $ordine->minuti = $oreMinutiSecondi['minuti'];
            $ordine->secondi = $oreMinutiSecondi['secondi'];
        }

        $prodotti = Prodotto::all();

        return view('ordineproduzione.ordineproduzione', [ 'ordiniproduzione'=>$ordiniProduzione, 'prodotti'=>$prodotti ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $request->validate([
            'numeroordine' => 'required',
            'quantita' => 'required|min:1',
            'datascadenza' => 'required',
            'prodotto_id' => 'required|exists:prodotto,id'
        ]);

        try {
            $numeroOrdine = $request['numeroordine'];
            $dataScadenza = $request['datascadenza'];
            $prodottoId = $request['prodotto_id'];
            $quantita = $request['quantita'];

            $prodotto = Prodotto::find($prodottoId); //controllo ulteriore anche se controllo già all'inizio con il validate
            if (empty($prodotto)) {
                throw new \Exception('Prodotto non trovato');
            }
            $tempoProduzione = $prodotto->tempounitarioproduzione * $quantita;

            $ordineProduzione = new OrdineProduzione();
            $ordineProduzione->numeroordine = $numeroOrdine;
            $ordineProduzione->datascadenza = $dataScadenza;
            $ordineProduzione->prodotto_id = $prodottoId;
            $ordineProduzione->quantita = $quantita;
            $ordineProduzione->tempoproduzione = $tempoProduzione;
            $ordineProduzione->stato = OrdineProduzione::STATO_APERTO;

            $ordineProduzione->save();

            $request->session()->flash('status', 'Ordine di Produzione inserito correttamente');
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
            'quantita' => 'required|min:1',
            'datascadenza' => 'required',
            'prodotto_id' => 'required|exists:prodotto,id'
        ]);

        try {
            $id = request()->input('id');
            $ordineProduzione = OrdineProduzione::findOrFail($id);
            $ordineProduzione->datascadenza = request()->input('datascadenza');
            $ordineProduzione->prodotto_id = request()->input('prodotto_id');
            $ordineProduzione->quantita = request()->input('quantita');

            $prodotto = Prodotto::find(request()->input('prodotto_id'));
            if (empty($prodotto)) {
                throw new \Exception('Prodotto non trovato');
            }
            $tempoProduzione = $prodotto->tempounitarioproduzione * (request()->input('quantita'));
            $ordineProduzione->tempoproduzione = $tempoProduzione;

            $ordineProduzione->update();

            request()->session()->flash('status', 'Ordine di Produzione modificato correttamente');
        } catch (QueryException $e) {
            return new JsonResponse(['errors' => $e->errorInfo[2]]);
        } catch (\Exception $e) {
            dump($e->getMessage());die;
            return new JsonResponse(['errors' => $e->getMessage()]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $prodottoId
     * @return JsonResponse
     */
    public function delete($ordineProduzioneId) {
        try {
            $ordineProduzione = OrdineProduzione::findOrFail($ordineProduzioneId);

            $ordineProduzione->delete();

            request()->session()->flash('status', 'Ordine di Produzione eliminato correttamente');
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
    public function getOrdineProduzioneById($id) {
        $ordineProduzione = OrdineProduzione::find($id);

        $oreMinutiSecondi = $this->getOreMinutiSecondiFromSecondi($ordineProduzione->getAttribute('tempoproduzione'));
        $ordineProduzione->ore = $oreMinutiSecondi['ore'];
        $ordineProduzione->minuti = $oreMinutiSecondi['minuti'];
        $ordineProduzione->secondi = $oreMinutiSecondi['secondi'];

        return response()->json($ordineProduzione);
    }

    /**
     * @return JsonResponse
     */
    protected function getUltimoNumeroProduzione() {
        $numeroOrdineMax = OrdineProduzione::max('numeroordine');

        $nuovoNumeroOrdine = $numeroOrdineMax + 1;

        return response()->json($nuovoNumeroOrdine);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    protected function getTempoProduzioneByProdottoIdAndQuantita(Request $request)
    {
        $prodottoId = $request['prodotto_id'];
        $quantita = $request['quantita'];

        $prodotto = Prodotto::findOrFail($prodottoId);
        $tempoProduzione = $prodotto->tempounitarioproduzione * $quantita;
        $tempoProduzione = $this->getOreMinutiSecondiFromSecondi($tempoProduzione);

        return response()->json($tempoProduzione);
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
