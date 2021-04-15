@extends('layouts.app')
@section('title')
    Modifica Tipo Macchina
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Modifica Tipo Macchina</h2>
    </div>
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row mt-3">
        <div class="col-12">
            <form action="{{ route('tipomacchina.update', [ 'tipomacchina'=>$tipomacchina ]) }}" method="post">
                @csrf
                <div class="row form-group">
                    <div class="col-12">
                        <label for="codice">Codice: </label>
                        <input type="text" name="codice" class="form-control @if($errors->has('codice')) is-invalid @endif" value="{{ $tipomacchina->codice }}" >
                        @if($errors->has('codice'))
                            <span class="text-danger">{{ $errors->first('codice') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-12">
                        <label for="descrizione">Descrizione: </label>
                        <input type="text" name="descrizione" class="form-control @if($errors->has('descrizione')) is-invalid @endif" value="{{ $tipomacchina->descrizione }}" required>
                        @if($errors->has('descrizione'))
                            <span class="text-danger">{{ $errors->first('descrizione') }}</span>
                        @endif
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-50 float-right">Aggiorna</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
