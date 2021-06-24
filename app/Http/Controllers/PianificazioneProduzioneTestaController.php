<?php

namespace App\Http\Controllers;

use App\Models\OrdineProduzione;
use App\Models\PianificazioneProduzioneTesta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PianificazioneProduzioneTestaController extends Controller
{
    protected $erroreProcedura = false;
    protected $elencoErroriProcedura = [];

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function creaPianificazioneForm() {
        return view('pianificazioneproduzione.creapianificazioneform');
    }

    public function creaPianificazione(Request $request) {
        $request->validate([
            'nome' => 'required|unique:pianificazione_produzione_testa,nome',
            'datainizio' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $nome = $request['nome'];
            $descrizione = empty($request['descrizione']) ? '' : $request['descrizione'];
            $dataInizio = $request['datainizio'];

            $pianificazioneProduzioneTesta = new PianificazioneProduzioneTesta();
            $pianificazioneProduzioneTesta->nome = $nome;
            $pianificazioneProduzioneTesta->descrizione = $descrizione;
            $pianificazioneProduzioneTesta->datacreazione = new \DateTime();
            $pianificazioneProduzioneTesta->datainizio = $dataInizio;
            $pianificazioneProduzioneTesta->save();

            $pianificazioneId = $pianificazioneProduzioneTesta->id;

            $this->proceduraCreaPianificazione($pianificazioneId, $dataInizio);

            $request->session()->flash('status', 'Pianificazione Produzione creata correttamente');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse(['errors' => $e->getMessage()]);
        }

        return new JsonResponse(['success' => '1']);
    }

    protected function proceduraCreaPianificazione($pianificazioneId, $dataInizioProduzione) {
        $macchine = $this->readMacchine(); //questo array verrà modificato per tenere traccia della proceduara di calcolo della pianificazione
        $ordiniAperti = $this->readOrdiniAperti(); //array contenente gli ordini aperti da schedulare sulle macchine

        foreach ($ordiniAperti as $ordineAperto) {
            $macchinaIndex = $this->getMacchinaIndexConMinimoTempoUtilizzoETipoMacchinaAdeguato($ordineAperto['tipomacchina_id'], $macchine);
            if ($macchinaIndex === false) {
                $this->erroreProcedura = true;
                $this->elencoErroriProcedura[] = [
                    'numeroordine' => $ordineAperto['ordineproduzione_numerordine'],
                    'tipomacchina_codice' => $ordineAperto['tipomacchina_codice'],
                    'tipomacchina_descrizione' => $ordineAperto['tipomacchina_descrizione'],
                ];
                continue;
            }

            //assegnare ordine produzione alla macchina e aggiornare tempo produzione
            $dataFine = $this->getDataFineOrdine($dataInizioProduzione, $macchine[$macchinaIndex]['tempoutilizzo'] + $ordineAperto['ordineproduzione_tempoproduzione']);
            $macchine[$macchinaIndex]['tempoutilizzo'] = $macchine[$macchinaIndex]['tempoutilizzo'] + $ordineAperto['ordineproduzione_tempoproduzione'];
            $macchine[$macchinaIndex]['ordiniproduzione'][] = [
                'numeroordine' => $ordineAperto['ordineproduzione_numerordine'],
                'prodotto_codice' => $ordineAperto['prodotto_codice'],
                'prodotto_descrizione' => $ordineAperto['prodotto_descrizione'],
                'quantita' => $ordineAperto['ordineproduzione_quantita'],
                'datascadenza' => $ordineAperto['ordineproduzione_datascadenza'],
                'datainizio' => $dataInizioProduzione,
                'datafine' => $dataFine
            ];
        }

        dump($macchine);die;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function readMacchine() {
        /** @var Collection $macchine */
        $macchine = DB::table('macchina')
            ->leftJoin('tipo_macchina', 'macchina.tipomacchina_id', '=', 'tipo_macchina.id')
            ->select(
                'macchina.id AS macchina_id',
                'macchina.codice AS macchina_codice',
                'macchina.descrizione AS macchina_descrizione',
                'tipo_macchina.id AS tipomacchina_id',
                'tipo_macchina.codice AS tipomacchina_codice',
                'tipo_macchina.descrizione AS tipomacchina_descrizione',
                DB::raw('0 AS tempoutilizzo')
            )
            ->get();

        $macchine = $macchine->toArray();
        foreach ($macchine as &$macchina) {
            $macchina = json_decode(json_encode($macchina), true);
        }

        if (empty($macchine)) {
            throw new \Exception('Impossibile pianificare la produzione, nessuna macchina disponibile');
        }

        return $macchine;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function readOrdiniAperti() {
        $ordiniAperti = DB::table('ordine_produzione')
            ->leftJoin('prodotto', 'ordine_produzione.prodotto_id', '=', 'prodotto.id')
            ->leftJoin('tipo_macchina', 'prodotto.tipomacchina_id', '=', 'tipo_macchina.id')
            ->select(
                'ordine_produzione.id AS ordineproduzione_id',
                'ordine_produzione.numeroordine AS ordineproduzione_numerordine',
                'ordine_produzione.quantita AS ordineproduzione_quantita',
                'ordine_produzione.tempoproduzione AS ordineproduzione_tempoproduzione',
                'ordine_produzione.datascadenza AS ordineproduzione_datascadenza',
                'prodotto.id AS prodotto_id',
                'prodotto.codice AS prodotto_codice',
                'prodotto.descrizione AS prodotto_descrizione',
                'tipo_macchina.id AS tipomacchina_id',
                'tipo_macchina.codice AS tipomacchina_codice',
                'tipo_macchina.descrizione AS tipomacchina_descrizione',
            )
            ->where('stato', '=', OrdineProduzione::STATO_APERTO)
            ->orderBy('datascadenza', 'asc')
            ->orderBy('tempoproduzione','desc')
            ->get();

        $ordiniAperti = $ordiniAperti->toArray();
        foreach ($ordiniAperti as &$ordineAperto) {
            $ordineAperto = json_decode(json_encode($ordineAperto), true);
        }

        if (empty($ordiniAperti)) {
            throw new \Exception('Impossibile pianificare la produzione, nessun ordine di produzione aperto');
        }

        return $ordiniAperti;
    }

    /**
     * @param $tipoMacchinaId
     * @param $macchine
     * @return false|int|string
     */
    protected function getMacchinaIndexConMinimoTempoUtilizzoETipoMacchinaAdeguato($tipoMacchinaId, $macchine) {
        $tempoMinimo = false;
        $macchinaIndex = false;
        foreach ($macchine as $index => $macchina) {
            if ($macchina['tipomacchina_id'] == $tipoMacchinaId) {
                if ($tempoMinimo === false || ($tempoMinimo > $macchina['tempoutilizzo'])) {
                    $tempoMinimo = $macchina['tempoutilizzo'];
                    $macchinaIndex = $index;
                }
            }
        }

        return $macchinaIndex;
    }

    protected function getDataFineOrdine($dataInizioProduzione, $tempoProduzioneTot) {
        $dataInizioProduzione = \DateTime::createFromFormat('Y-m-d', $dataInizioProduzione);
        $dataInizioProduzione->setTime(0, 0, 0);

        $dataFine = $dataInizioProduzione->add(new \DateInterval('PT' . $tempoProduzioneTot . 'S'));
        return ($dataFine->format('Y-m-d'));
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
