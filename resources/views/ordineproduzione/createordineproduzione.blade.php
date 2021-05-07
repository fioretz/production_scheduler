{{-- Create Ordine Produzione --}}
<div class="modal fade" id="createOrdineProduzioneModal" tabindex="-1" aria-labelledby="createOrdineProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOrdineProduzioneModalLabel">Nuovo Ordine di Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createOrdineProduzioneForm">
                <div class="modal-body">
                    <div id="createErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createNumeroOrdine">Numero Ordine: </label>
                            <input type="text" name="createNumeroOrdine" id="createNumeroOrdine" class="form-control @if($errors->has('numeroordine')) is-invalid @endif" value="{{ old('numeroordine') }}">
                            <span class="text-danger">
                                <strong id="create-numeroordine-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createProdotto">Prodotto: </label>
                            <select id="createProdotto" class="selectpicker show-tick form-control border" data-live-search="true" data-style="btn-white">
                                <option value="" selected="selected"></option>
                                @foreach($prodotti as $row)
                                    <option value="{{ $row->id }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                    <strong id="create-prodotto_id-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createQuantita">Quantità: </label>
                            <input type="text" name="createQuantita" id="createQuantita" class="form-control @if($errors->has('quantita')) is-invalid @endif" value="{{ old('quantita') }}">
                            <span class="text-danger">
                                    <strong id="create-quantita-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label>Tempo Produzione: </label>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="createOre">Ore: </label>
                                    <input type="text" name="createOre" id="createOre" class="form-control text-right" placeholder="0" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="createMinuti">Minuti: </label>
                                    <input type="text" name="createMinuti" id="createMinuti" class="form-control text-right" placeholder="0" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="createSecondi">Secondi: </label>
                                    <input type="text" name="createSecondi" id="createSecondi" class="form-control text-right" placeholder="0" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12 date">
                            <label for="createDataScadenza">Data Scadenza: </label>
                            <div class="input-group mb-2 col-6">
                                <input type="text" class="form-control" id="createDataScadenza" name="event_date" placeholder="dd/mm/yyyy" autocomplete="off" >
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <span class="text-danger">
                                <strong id="create-datascadenza-error"></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#createOrdineProduzioneModal').on('shown.bs.modal', function() {
        $('.date #createDataScadenza').datepicker({
            format: "dd/mm/yyyy",
            language: "it",
            orientation: "bottom left",
            todayHighlight: true,
            autoclose: true,
            container: '#createOrdineProduzioneModal'
        });

        $.get('/ordineproduzione/ultimonumeroordine', function(nuovoNumeroOrdine) {
            $('#createNumeroOrdine').val(nuovoNumeroOrdine);
        });
    });

    $('#createQuantita').on('change', function() {
        calcolaTempoProduzione();
    });
    $('#createProdotto').on('change', function() {
        calcolaTempoProduzione();
    });

    function calcolaTempoProduzione() {
        let quantita = $('#createQuantita').val();
        let prodottoId = $('#createProdotto').val();
        if (quantita !== "" && prodottoId !== "") {
            $.ajax({
                url: "{{ route('ordineproduzione.getTempoProduzioneByProdottoIdAndQuantita') }}",
                type: 'POST',
                data: {
                    prodotto_id: prodottoId,
                    quantita: quantita
                },
                success: function(response) {
                    $('#createOre').val(response.ore);
                    $('#createMinuti').val(response.minuti);
                    $('#createSecondi').val(response.secondi);
                }
            })
        }
    }

    $("#createOrdineProduzioneForm").submit(function(e){
        e.preventDefault();

        let numeroordine = $("#createNumeroOrdine").val();
        let prodottoId = $("#createProdotto").val();
        let quantita = $("#createQuantita").val();
        let dataScadenza = $("#createDataScadenza").val();
        if (dataScadenza !== '') {
            dataScadenza = moment(dataScadenza, "DD/MM/YYYY");
            dataScadenza = moment(dataScadenza).format('Y-MM-DD');
        }

        $("#create-numeroordine-error").html("");
        $("#create-prodotto_id-error").html("");
        $("#create-quantita-error").html("");
        $("#create-datascadenza-error").html("");

        $.ajax({
            url: "{{ route('ordineproduzione.store') }}",
            type: 'POST',
            data: {
                numeroordine: numeroordine,
                prodotto_id: prodottoId,
                quantita: quantita,
                datascadenza: dataScadenza
            },
            success: function(response) {
                if (response.errors) {
                    $('#createErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#createErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('ordineproduzione.show') }}"
                }
            },
            error: function (err) {
                if (err.status === 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        $('#create-'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                    });
                }
            }
        })
    });

    $("#createOrdineProduzioneModal").on('hidden.bs.modal', function () {
        $("#create-numeroordine-error").html("");
        $("#create-prodotto_id-error").html("");
        $("#create-quantita-error").html("");
        $("#create-datascadenza-error").html("");
        $("#createErrorLabel").fadeOut();
    })
</script>
