<?php

namespace App\Http\Controllers;

use App\Models\OrdineProduzione;
use App\Models\PianificazioneProduzioneMacchina;
use App\Models\PianificazioneProduzioneOrdine;
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

    public function show() {
        $pianificazioniProduzione = PianificazioneProduzioneTesta::all();

        return view('pianificazioneproduzione.pianificazioneproduzione', [ 'data'=>$pianificazioniProduzione ]);
    }

    public function getPianificazioneById($id) {
        $pianificazioneProduzioneTesta = PianificazioneProduzioneTesta::where('id', $id)->first();

        $pianificazioneProduzione = DB::table('pianificazione_produzione_macchina')
            ->leftJoin('pianificazione_produzione_ordine', 'pianificazione_produzione_macchina.id', '=', 'pianificazione_produzione_ordine.pianprodmacchina_id')
            ->select(
                'pianificazione_produzione_macchina.id AS pianprodmacchina_id',
                'pianificazione_produzione_macchina.pianprodtesta_id AS pianprodtesta_id',
                'pianificazione_produzione_macchina.macchina_codice AS macchina_codice',
                'pianificazione_produzione_macchina.macchina_descrizione AS macchina_descrizione',
                'pianificazione_produzione_macchina.tipomacchina_codice AS tipomacchina_codice',
                'pianificazione_produzione_macchina.tipomacchina_descrizione AS tipomacchina_descrizione',
                'pianificazione_produzione_macchina.tempoutilizzo AS tempoutilizzo',
                'pianificazione_produzione_ordine.numeroordine AS numeroordine',
                'pianificazione_produzione_ordine.prodotto_codice AS prodotto_codice',
                'pianificazione_produzione_ordine.prodotto_descrizione AS prodotto_descrizione',
                'pianificazione_produzione_ordine.quantita AS ordine_quantita',
                'pianificazione_produzione_ordine.datascadenza AS datascadenza',
                'pianificazione_produzione_ordine.datainizio AS datainizio',
                'pianificazione_produzione_ordine.datafine AS datafine',
                'pianificazione_produzione_ordine.sequenza AS sequenza'
            )
            ->where('pianificazione_produzione_macchina.pianprodtesta_id', '=', $id)
            ->orderBy('pianificazione_produzione_macchina.macchina_codice', 'asc')
            ->orderBy('pianificazione_produzione_ordine.sequenza', 'asc')
            ->get();

        //appiattisco la struttura $pianificazioneProduzione per poterla utilizzare meglio lato client
        $pianificazioneProduzione = $pianificazioneProduzione->toArray();
        foreach ($pianificazioneProduzione as &$row) {
            $row = json_decode(json_encode($row), true);
        }

        $pianificazioneProduzioneStrutturaPiatta = [];
        $macchinaCodice = '';
        foreach ($pianificazioneProduzione as $rigaPianificazione) {
            //inserisco una nuova macchina solo quando ne incontro una diversa dalla precedente
            if ($rigaPianificazione['macchina_codice'] != $macchinaCodice) {
                $macchinaCodice = $rigaPianificazione['macchina_codice'];
                $pianificazioneProduzioneStrutturaPiatta[$macchinaCodice] = [
                    'pianprodmacchina_id' => $rigaPianificazione['pianprodmacchina_id'],
                    'pianprodtesta_id' => $rigaPianificazione['pianprodtesta_id'],
                    'macchina_codice' => $rigaPianificazione['macchina_codice'],
                    'macchina_descrizione' => $rigaPianificazione['macchina_descrizione'],
                    'tipomacchina_codice' => $rigaPianificazione['tipomacchina_codice'],
                    'tipomacchina_descrizione' => $rigaPianificazione['tipomacchina_descrizione'],
                    'tempoutilizzo' => $rigaPianificazione['tempoutilizzo'],
                ];
            }
            //aggiungo ad ogni macchina i suoi ordini di produzione
            $pianificazioneProduzioneStrutturaPiatta[$macchinaCodice]['ordiniproduzione'][] = [
                'numeroordine' => $rigaPianificazione['numeroordine'],
                'prodotto_codice' => $rigaPianificazione['prodotto_codice'],
                'prodotto_descrizione' => $rigaPianificazione['prodotto_descrizione'],
                'ordine_quantita' => $rigaPianificazione['ordine_quantita'],
                'datascadenza' => $rigaPianificazione['datascadenza'],
                'datainizio' => $rigaPianificazione['datainizio'],
                'datafine' => $rigaPianificazione['datafine'],
                'sequenza' => $rigaPianificazione['sequenza'],
                'erroreScadenza' => ($rigaPianificazione['datascadenza'] < $rigaPianificazione['datafine']) //controllo se l'ordina verrà completato dopo la data di scadenza
            ];
        }

//        dump($pianificazioneProduzioneTesta, $pianificazioneProduzioneStrutturaPiatta);die;

        if (empty($pianificazioneProduzioneTesta)) {
            return view('pianificazioneproduzione.visualizzapianificazioneproduzioneerrore');
        }

        return view(
            'pianificazioneproduzione.visualizzapianificazioneproduzione',
            [
                'pianificazioneTesta' => $pianificazioneProduzioneTesta,
                'pianificazioneRighe' => $pianificazioneProduzioneStrutturaPiatta
            ]
        );
    }

    public function delete($pianificazioneProduzioneId) {
        DB::beginTransaction();
        try {
            //delete pianificazione_produzione_ordine
            $pianificazioneProduzioneMacchina = DB::table('pianificazione_produzione_macchina')->where('pianprodtesta_id', $pianificazioneProduzioneId)->get();
            foreach ($pianificazioneProduzioneMacchina as $row) {
                DB::table("pianificazione_produzione_ordine")->where('pianprodmacchina_id', $row->id)->delete();
            }

            //delete pianificazione produzione macchina
            DB::table("pianificazione_produzione_macchina")->where('pianprodtesta_id', $pianificazioneProduzioneId)->delete();

            //delete pianificazione produzione testa
            DB::table("pianificazione_produzione_testa")->where('id', $pianificazioneProduzioneId)->delete();

            DB::commit();
            request()->session()->flash('status', 'Pianificazione di Produzione eliminata correttamente');
        } catch (\Exception $e) {
            DB::rollBack();
            request()->session()->flash('deleteError', $e->getMessage());
        }

        return new JsonResponse(['success' => '1']);
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

            //lancio procedura calcolo pianificazione produzione, ottengo la struttura dati da persistere a db
            $macchine = $this->proceduraCreaPianificazione($dataInizio);

            //persisto la struttura dati su db nelle tabelle pianificazione_produzione_macchina e pianificazione_produzione_ordine
            $this->persistDataPianificazioneProduzione($pianificazioneId, $macchine);

            $request->session()->flash('status', 'Pianificazione Produzione creata correttamente');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse(['errors' => $e->getMessage()]);
        }

        return new JsonResponse(['success' => '1']);
    }

    /**
     * @param $pianificazioneId
     * @param $dataInizioProduzione
     * @return array
     * @throws \Exception
     */
    protected function proceduraCreaPianificazione($dataInizioProduzione) {
        $macchine = $this->readMacchine(); //questo array verrà modificato per tenere traccia della proceduara di calcolo della pianificazione
        $ordiniAperti = $this->readOrdiniAperti(); //array contenente gli ordini aperti da schedulare sulle macchine

        //scandisco tutti gli ordini di produzione aperti per allocarli alle macchine disponibili
        foreach ($ordiniAperti as $ordineAperto) {
            //ottengo l'indice della macchina più scarica compatibile con il prodotto che si vuole produrre
            $macchinaIndex = $this->getMacchinaIndexConMinimoTempoUtilizzoETipoMacchinaAdeguato($ordineAperto['tipomacchina_id'], $macchine);
            //se non esiste una macchina alla quale assegnare un ordine di produzione tengo traccia dell'errore
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

        //compongo la stringa degli errori da visualizzare lato client all'utente
        if ($this->erroreProcedura) {
            $errorMessage = 'Errore schedulazione Ordini di Produzione, non si dispone di una macchina a cui assegnare i seguenti ordini:';
            foreach ($this->elencoErroriProcedura as $errore) {
                $errorMessage = $errorMessage . ' ' . $errore['numeroordine'] . ',';
            }
            $errorMessage = substr($errorMessage, 0, -1);
            throw new \Exception($errorMessage);
        }

        return $macchine;
    }

    protected function persistDataPianificazioneProduzione($pianificazioneId, $macchine) {
        //scandisco tutte le macchine a cui ho assegnato un ordine di produzione da produrre
        foreach ($macchine as $macchina) {
            //controllo che alla macchina siano assegnati degli ordini di produzione
            if (array_key_exists('ordiniproduzione', $macchina)) {
                //creo record tabella pianificazione_produzione_macchina
                $pianificazioneProduzioneMacchina = new PianificazioneProduzioneMacchina();
                $pianificazioneProduzioneMacchina->macchina_codice = $macchina['macchina_codice'];
                $pianificazioneProduzioneMacchina->macchina_descrizione = $macchina['macchina_descrizione'];
                $pianificazioneProduzioneMacchina->tipomacchina_codice = $macchina['tipomacchina_codice'];
                $pianificazioneProduzioneMacchina->tipomacchina_descrizione = $macchina['tipomacchina_descrizione'];
                $pianificazioneProduzioneMacchina->tempoutilizzo = $macchina['tempoutilizzo'];
                $pianificazioneProduzioneMacchina->pianprodtesta_id = $pianificazioneId;
                $pianificazioneProduzioneMacchina->save();

                $pianificazioneMacchinaId = $pianificazioneProduzioneMacchina->id;

                //creo un record tabella pianificazione_produzione_ordine per ogni ordine di produzione assegnato alla macchina
                $sequenza = 1;
                foreach ($macchina['ordiniproduzione'] as $ordineProduzione) {
                    $pianificazioneProduzioneOrdine = new PianificazioneProduzioneOrdine();
                    $pianificazioneProduzioneOrdine->numeroordine = $ordineProduzione['numeroordine'];
                    $pianificazioneProduzioneOrdine->prodotto_codice = $ordineProduzione['prodotto_codice'];
                    $pianificazioneProduzioneOrdine->prodotto_descrizione = $ordineProduzione['prodotto_descrizione'];
                    $pianificazioneProduzioneOrdine->quantita = $ordineProduzione['quantita'];
                    $pianificazioneProduzioneOrdine->datascadenza = $ordineProduzione['datascadenza'];
                    $pianificazioneProduzioneOrdine->datainizio = $ordineProduzione['datainizio'];
                    $pianificazioneProduzioneOrdine->datafine = $ordineProduzione['datafine'];
                    $pianificazioneProduzioneOrdine->pianprodmacchina_id = $pianificazioneMacchinaId;
                    $pianificazioneProduzioneOrdine->sequenza = $sequenza;
                    $pianificazioneProduzioneOrdine->save();
                    $sequenza += 1;
                }
            }
        }
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
