@extends('layouts.app')
@section('title')
    Crea Pianificazione Produzione
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Crea Pianificazione Produzione</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div id="CreatePianificazioneErrorLabel" class="alert alert-danger" style="display: none" role="alert"></div>
    <form id="createPianificazioneForm">
        <div class="form-group col-3">
            <label for="createNome">Nome: </label>
            <input type="text" name="createNome" id="createNome" class="form-control @if($errors->has('nome')) is-invalid @endif" value="{{ old('nome') }}">
            <span class="text-danger">
                <strong id="create-nome-error"></strong>
            </span>
        </div>
        <div class="form-group col-3">
            <label for="createDescrizione">Descrizione: </label>
            <textarea name="createDescrizione" id="createDescrizione" class="form-control @if($errors->has('descrizione')) is-invalid @endif" rows="4"></textarea>
            <span class="text-danger">
                <strong id="create-descrizione-error"></strong>
            </span>
        </div>
        <div class="form-group col-3">
            <div class="date">
                <label for="createDataInizio">Data Inizio Produzione Stimata: </label>
                <div class="input-group mb-2 col-6">
                    <input type="text" class="form-control" id="createDataInizio" name="event_date" placeholder="dd/mm/yyyy" autocomplete="off" >
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <span class="text-danger">
                                <strong id="create-datainizio-error"></strong>
                            </span>
            </div>
        </div>
        <div class="col-3">
            <button class="btn btn-primary btn-lg float-right" type="submit">Crea</button>
        </div>
    </form>

    <div id="loaderMessage" class="modal" tabindex="-1" role="dialog" style="display:none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ asset('images/loader.gif') }}" style="width: 100px"/>
                    <p>Creazione Pianificazione in corso...</p>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            $('.date #createDataInizio').datepicker({
                format: "dd/mm/yyyy",
                language: "it",
                orientation: "bottom left",
                todayHighlight: true,
                autoclose: true,
                container: '.container-fluid'
            });
        });

        $("#createPianificazioneForm").submit(function(e) {
            e.preventDefault();

            let nome = $("#createNome").val();
            let descrizione = $("#createDescrizione").val();
            let dataInizio = $("#createDataInizio").val();
            if (dataInizio !== '') {
                dataInizio = moment(dataInizio, "DD/MM/YYYY");
                dataInizio = moment(dataInizio).format('Y-MM-DD');
            }

            $("#create-nome-error").html("");
            $("#create-descrizione-error").html("");
            $("#create-datainizio-error").html("");

            $.blockUI({
                css: {
                    border: '0px',
                },
                message: $('#loaderMessage')
            });

            $(".navbar").block({
                message: ''
            });

            $.ajax({
                url: "{{ route('pianificazioneproduzione.creaPianificazione') }}",
                type: 'POST',
                data: {
                    nome: nome,
                    descrizione: descrizione,
                    datainizio: dataInizio
                },
                success: function(response) {
                    if (response.errors) {
                        $('#CreatePianificazioneErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#CreatePianificazioneErrorLabel\').fadeOut()">&times;</button>'
                        ).fadeIn();
                    } else {
                        window.location.href = "{{ route('pianificazioneproduzione.creaPianificazioneForm') }}"
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            $('#create-'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                        });
                    }
                },
                complete: function() {
                    $.unblockUI();
                    $(".navbar").unblock();
                }
            })


        })


    </script>


@endsection
