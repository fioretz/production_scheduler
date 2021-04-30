{{-- Edit Tipo Macchina --}}
<div class="modal fade" id="editMacchinaModal" tabindex="-1" aria-labelledby="editMacchinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMacchinaModalLabel">Modifica Macchina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editMacchinaForm">
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
    function editMacchina(id)
    {
        $.get('/macchina/'+id, function(data) {
            $('#editId').val(data.macchina.id);
            $('#editCodice').val(data.macchina.codice);
            $('#editDescrizione').val(data.macchina.descrizione);
            $('#editTipoMacchina').selectpicker('val', data.macchina.tipomacchina_id);
            $('#editMacchinaModal').modal('toggle');
        })
    }

    $("#editMacchinaForm").submit(function(e){
        e.preventDefault();

        let id = $("#editId").val();
        let codice = $("#editCodice").val();
        let descrizione = $("#editDescrizione").val();
        let tipoMacchinaId = $("#editTipoMacchina").val();

        $("#edit-codice-error").html("");
        $("#edit-descrizione-error").html("");
        $("#edit-tipomacchina_id-error").html("");

        $.ajax({
            url: "{{ route('macchina.update') }}",
            type: 'POST',
            data: {
                id: id,
                codice: codice,
                descrizione: descrizione,
                tipomacchina_id: tipoMacchinaId
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('macchina.show') }}"
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

    $("#editMacchinaModal").on('hidden.bs.modal', function () {
        $("#edit-codice-error").html("");
        $("#edit-descrizione-error").html("");
        $("#edit-tipomacchina_id-error").html("");
        $('#editErrorLabel').fadeOut();
    })
</script>
