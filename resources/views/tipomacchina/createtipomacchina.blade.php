{{-- Create Tipo Macchina --}}
<div class="modal fade" id="createTipoMacchinaModal" tabindex="-1" aria-labelledby="createTipoMacchinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTipoMacchinaModalLabel">Nuovo Tipo Macchina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createTipoMacchinaForm">
                <div class="modal-body">
                    <div id="createErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createCodice">Codice: </label>
                            <input type="text" name="createCodice" id="createCodice" class="form-control @if($errors->has('codice')) is-invalid @endif" value="{{ old('codice') }}">
                            <span class="text-danger">
                                <strong id="create-codice-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createDescrizione">Descrizione: </label>
                            <input type="text" name="createDescrizione" id="createDescrizione" class="form-control @if($errors->has('descrizione')) is-invalid @endif" value="{{ old('descrizione') }}">
                            <span class="text-danger">
                                    <strong id="create-descrizione-error"></strong>
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
    $("#createTipoMacchinaForm").submit(function(e){
        e.preventDefault();

        let codice = $("#createCodice").val();
        let descrizione = $("#createDescrizione").val();
        let _token = $("input[name=_token]").val();

        $("#crete-codice-error").html("");
        $("#create-descrizione-error").html("");

        $.ajax({
            url: "{{ route('tipomacchina.store') }}",
            type: 'POST',
            data: {
                codice: codice,
                descrizione: descrizione,
                _token: _token
            },
            success: function(response) {
                if (response.errors) {
                    $('#createErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#createErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('tipomacchina.show') }}"
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

    $("#createTipoMacchinaModal").on('hidden.bs.modal', function () {
        $("#create-codice-error").html("");
        $("#create-descrizione-error").html("");
        $('#createErrorLabel').fadeOut();
    })
</script>
