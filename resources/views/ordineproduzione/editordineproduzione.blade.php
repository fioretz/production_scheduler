{{-- Edit Ordine Produzione --}}
<div class="modal fade" id="editOrdineProduzioneModal" tabindex="-1" aria-labelledby="editOrdineProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrdineProduzioneModalLabel">Modifica Ordine di Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editOrdineProduzioneForm">
                <div class="modal-body">
                    <div id="editErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <input type="hidden" id="editId" name="editId" />
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editNumeroOrdine">Numero Ordine: </label>
                            <input type="text" name="editNumeroOrdine" id="editNumeroOrdine" class="form-control @if($errors->has('numeroordine')) is-invalid @endif" value="{{ old('numeroordine') }}" disabled>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editProdotto">Prodotto: </label>
                            <select id="editProdotto" class="selectpicker show-tick form-control border" data-live-search="true" data-style="btn-white">
                                @foreach($prodotti as $row)
                                    <option value="{{ $row->id }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                    <strong id="edit-prodotto_id-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editQuantita">Quantità: </label>
                            <input type="text" name="editQuantita" id="editQuantita" class="form-control @if($errors->has('quantita')) is-invalid @endif" value="{{ old('quantita') }}">
                            <span class="text-danger">
                                    <strong id="edit-quantita-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label>Tempo Produzione: </label>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="editOre">Ore: </label>
                                    <input type="text" name="editOre" id="editOre" class="form-control text-right" placeholder="0" value="{{ old('ore') }}" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="editMinuti">Minuti: </label>
                                    <input type="text" name="editMinuti" id="editMinuti" class="form-control text-right" placeholder="0" value="{{ old('minuti') }}" disabled>
                                </div>
                                <div class="col-3">
                                    <label for="editSecondi">Secondi: </label>
                                    <input type="text" name="editSecondi" id="editSecondi" class="form-control text-right" placeholder="0" value="{{ old('secondi') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12 date">
                            <label for="editDataScadenza">Data Scadenza: </label>
                            <div class="input-group mb-2 col-6">
                                <input type="text" class="form-control" id="editDataScadenza" name="event_date" placeholder="dd/mm/yyyy" autocomplete="off" >
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <span class="text-danger">
                                <strong id="edit-datascadenza-error"></strong>
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

<script>
    $('#editOrdineProduzioneModal').on('shown.bs.modal', function() {
        $('.date #editDataScadenza').datepicker({
            format: "dd/mm/yyyy",
            language: "it",
            orientation: "bottom left",
            todayHighlight: true,
            autoclose: true,
            container: '#editOrdineProduzioneModal'
        });
    });

    $('#editQuantita').on('change', function() {
        calcolaTempoProduzioneEdit();
    });
    $('#editProdotto').on('change', function() {
        calcolaTempoProduzioneEdit();
    });

    function calcolaTempoProduzioneEdit() {
        let quantita = $('#editQuantita').val();
        let prodottoId = $('#editProdotto').val();
        if (quantita !== "" && prodottoId !== "") {
            $.ajax({
                url: "{{ route('ordineproduzione.getTempoProduzioneByProdottoIdAndQuantita') }}",
                type: 'POST',
                data: {
                    prodotto_id: prodottoId,
                    quantita: quantita
                },
                success: function(response) {
                    $('#editOre').val(response.ore);
                    $('#editMinuti').val(response.minuti);
                    $('#editSecondi').val(response.secondi);
                }
            })
        }
    }

    function editOrdineProduzione(id)
    {
        $.get('/ordineproduzione/'+id, function(ordineProduzione) {
            $('#editId').val(ordineProduzione.id);
            $('#editNumeroOrdine').val(ordineProduzione.numeroordine);
            $('#editProdotto').val(ordineProduzione.prodotto_id);
            $('#editQuantita').val(ordineProduzione.quantita);
            $('#editOre').val(ordineProduzione.ore);
            $('#editMinuti').val(ordineProduzione.minuti);
            $('#editSecondi').val(ordineProduzione.secondi);
            let dataScadenza = moment(ordineProduzione.datascadenza, 'Y-MM-DD');
            dataScadenza = moment(dataScadenza).format('DD/MM/YYYY');
            $('#editDataScadenza').val(dataScadenza);
            $('#editOrdineProduzioneModal').modal('toggle');
        })
    }

    $("#editOrdineProduzioneForm").submit(function(e){
        e.preventDefault();

        let id = $("#editId").val();
        let prodottoId = $("#editProdotto").val();
        let quantita = $("#editQuantita").val();
        let dataScadenza = $("#editDataScadenza").val();
        if (dataScadenza !== '') {
            dataScadenza = moment(dataScadenza, "DD/MM/YYYY");
            dataScadenza = moment(dataScadenza).format('Y-MM-DD');
        }

        $("#edit-quantita-error").html("");
        $("#edit-prodotto_id-error").html("");
        $("#edit-datascadenza-error").html("");

        $.ajax({
            url: "{{ route('ordineproduzione.update') }}",
            type: 'POST',
            data: {
                id: id,
                prodotto_id: prodottoId,
                quantita: quantita,
                datascadenza: dataScadenza
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('ordineproduzione.show') }}"
                }
            },
            error: function (err) {
                if (err.status === 422) {
                    $.each(err.responseJSON.errors, function (i, error) {
                        $('#edit-'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                    });
                }
            }
        })
    });

    $("#editOrdineProduzioneModal").on('hidden.bs.modal', function () {
        $("#edit-quantita-error").html("");
        $("#edit-prodotto_id-error").html("");
        $("#edit-datascadenza-error").html("");
        $('#editErrorLabel').fadeOut();
    })
</script>
