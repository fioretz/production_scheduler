{{-- Edit Prodotto --}}
<div class="modal fade" id="editProdottoModal" tabindex="-1" aria-labelledby="editProdottoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProdottoModalLabel">Modifica Prodotto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editProdottoForm">
                <div class="modal-body">
                    <div id="editErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <input type="hidden" id="editId" name="editId" />
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editCodice">Codice: </label>
                            <input type="text" name="editCodice" id="editCodice" class="form-control @if($errors->has('codice')) is-invalid @endif" value="{{ old('codice') }}">
                            <span class="text-danger">
                                    <strong id="edit-codice-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editDescrizione">Descrizione: </label>
                            <input type="text" name="editDescrizione" id="editDescrizione" class="form-control @if($errors->has('descrizione')) is-invalid @endif" value="{{ old('descrizione') }}">
                            <span class="text-danger">
                                    <strong id="edit-descrizione-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label>Tempo Produzione unitario: </label>
                            <div class="form-row">
                                <div class="col-3">
                                    <label for="editOre">Ore: </label>
                                    <input type="text" name="editOre" id="editOre" class="form-control text-right" placeholder="0" value="{{ old('ore') }}">
                                </div>
                                <div class="col-3">
                                    <label for="editMinuti">Minuti: </label>
                                    <input type="text" name="editMinuti" id="editMinuti" class="form-control text-right" placeholder="0" value="{{ old('minuti') }}">
                                </div>
                                <div class="col-3">
                                    <label for="editSecondi">Secondi: </label>
                                    <input type="text" name="editSecondi" id="editSecondi" class="form-control text-right" placeholder="0" value="{{ old('secondi') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editTipoMacchina">Tipo Macchina: </label>
                            <select id="editTipoMacchina" class="selectpicker show-tick form-control border" data-live-search="true" data-style="btn-white">
                                @foreach($tipomacchina as $row)
                                    <option value="{{ $row->id }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                    <strong id="edit-tipomacchina_id-error"></strong>
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
    function editProdotto(id)
    {
        $.get('/prodotto/'+id, function(prodotto) {
            $('#editId').val(prodotto.id);
            $('#editCodice').val(prodotto.codice);
            $('#editDescrizione').val(prodotto.descrizione);
            $('#editOre').val(prodotto.ore);
            $('#editMinuti').val(prodotto.minuti);
            $('#editSecondi').val(prodotto.secondi);
            $('#editTipoMacchina').selectpicker('val', prodotto.tipomacchina_id);
            $('#editProdottoModal').modal('toggle');
        })
    }

    $("#editProdottoForm").submit(function(e){
        e.preventDefault();

        let id = $("#editId").val();
        let codice = $("#editCodice").val();
        let descrizione = $("#editDescrizione").val();
        let ore = $("#editOre").val();
        let minuti = $("#editMinuti").val();
        let secondi = $("#editSecondi").val();
        let tipoMacchinaId = $("#editTipoMacchina").val();

        $("#edit-codice-error").html("");
        $("#edit-descrizione-error").html("");
        $("#edit-tipomacchina_id-error").html("");

        $.ajax({
            url: "{{ route('prodotto.update') }}",
            type: 'POST',
            data: {
                id: id,
                codice: codice,
                descrizione: descrizione,
                ore: ore,
                minuti: minuti,
                secondi: secondi,
                tipomacchina_id: tipoMacchinaId
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('prodotto.show') }}"
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

    $("#editProdottoModal").on('hidden.bs.modal', function () {
        $("#edit-codice-error").html("");
        $("#edit-descrizione-error").html("");
        $("#edit-tipomacchina_id-error").html("");
        $('#editErrorLabel').fadeOut();
    })
</script>
