@extends('layouts.app')
@section('title')
    Visualizza Pianificazione Produzione Errore
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Pianificazione Produzione</h2>
    </div>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Pianificazione Produzione non trovata</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endsection
