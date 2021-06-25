@extends('layouts.app')
@section('title')
    Visualizza Pianificazione Produzione
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Pianificazione Produzione - {{ $pianificazioneTesta->nome }}</h2>
    </div>

    <br>
    <div class="form-group col-3">
        <label for="descrizione">Descrizione: </label>
        <textarea name="descrizione" id="descrizione" class="form-control" rows="4" disabled>{{ $pianificazioneTesta->descrizione }}</textarea>
    </div>
    <div class="form-group col-3">
        <label for="datainizio">Data Inizio: </label>
        {{ date('d/m/Y', strtotime($pianificazioneTesta->datainizio)) }}
    </div>

    @foreach($pianificazioneRighe as $macchina)
        <div class="d-flex justify-content-between align-items-center pt-3 pb-1 mb-3 border-bottom">
            <h4>{{ $macchina['macchina_codice'] }} - {{ $macchina['macchina_descrizione'] }}</h4>
        </div>
        <div class="ordiniproduzione-group pb-1 mb-5">
            <div class="row">
                @foreach($macchina['ordiniproduzione'] as $ordine)
                    <div class="card col-4" style="width: 18rem; @if ($ordine['erroreScadenza']) background-color: #f9d6d5; @else background-color: #d7f3e3; @endif">
                        <div class="card-body">
                            @if ($ordine['erroreScadenza']) <span title="Scadenza non rispettata"><i class="fa fa-exclamation-triangle badge-errore-scadenza" aria-hidden="true"></i></span> @endif
                            <h5 class="card-title">Numero Ordine: {{ $ordine['numeroordine'] }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $ordine['prodotto_codice'] }} - {{ $ordine['prodotto_descrizione'] }}</h6>
                            <br>
                            Quantità: {{ $ordine['ordine_quantita'] }} <br>
                            Data Scadenza: {{ date('d/m/Y', strtotime($ordine['datascadenza'])) }} <br>
                            Data Fine: {{ date('d/m/Y', strtotime($ordine['datafine'])) }} <br><br>
                            Sequenza: {{ $ordine['sequenza'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

@endsection





