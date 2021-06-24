@extends('layouts.app')
@section('title')
    Visualizza Pianificazione Produzione
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Visualizza Pianificazione Produzione</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('deleteError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('deleteError') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div id="VisualizzaPianificazioneErrorLabel" class="alert alert-danger" style="display: none" role="alert"></div>
    <form id="visualizzaPianificazioneForm">
        <div class="form-row">
            <div class="col-4">
                <label for="pianificazione">Seleziona Pianificazione Produzione: </label>
                <select id="pianificazione" class="selectpicker show-tick form-control border" data-live-search="true" data-style="btn-white">
                    <option value="" selected="selected"></option>
                    @foreach($data as $row)
                        <option value="{{ $row->id }}">{{ $row->nome }} - Inizio Produzione: {{ date('d/m/Y', strtotime($row->datainizio)) }}</option>
                    @endforeach
                </select>
                <span class="text-danger">
                    <strong id="select-pianificazione-error"></strong>
                </span>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-success" onclick="showPianificazioneById()" style="margin-top:34px">Visualizza</button>
                <button type="button" class="btn btn-danger" onclick="deletePianificazioneById()" style="margin-top:34px"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </form>

    <script>

    </script>

    @include('pianificazioneproduzione.deletepianificazioneproduzione')
    @include('pianificazioneproduzione.visualizzapianificazioneproduzione')

@endsection
