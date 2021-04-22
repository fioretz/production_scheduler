{{-- Edit Tipo Macchina --}}
<div class="modal fade" id="editTipoMacchinaModal" tabindex="-1" aria-labelledby="editTipoMacchinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTipoMacchinaModalLabel">Modifica Tipo Macchina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTipoMacchinaForm">
                <div class="modal-body">
                    <div id="editErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <input type="hidden" id="id" name="id" />
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
    function editTipoMacchina(id)
    {
        $.get('/tipomacchina/'+id, function(tipomacchina) {
            $('#id').val(tipomacchina.id);
            $('#editCodice').val(tipomacchina.codice);
            $('#editDescrizione').val(tipomacchina.descrizione);
            $('#editTipoMacchinaModal').modal('toggle');
        })
    }

    $("#editTipoMacchinaForm").submit(function(e){
        e.preventDefault();

        let id = $("#id").val();
        let codice = $("#editCodice").val();
        let descrizione = $("#editDescrizione").val();
        let _token = $("input[name=_token]").val();

        $("#edit-codice-error").html("");
        $("#edit-descrizione-error").html("");

        $.ajax({
            url: "{{ route('tipomacchina.update') }}",
            type: 'POST',
            data: {
                id: id,
                codice: codice,
                descrizione: descrizione,
                _token: _token
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('tipomacchina.show') }}"
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
</script>
