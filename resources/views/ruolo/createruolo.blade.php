{{-- Create Ruolo --}}
<div class="modal fade" id="createRuoloModal" tabindex="-1" aria-labelledby="createRuoloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRuoloModalLabel">Nuovo Ruolo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createRuoloForm">
                <div class="modal-body">
                    <div id="createErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createName">Nome: </label>
                            <input type="text" name="createName" id="createName" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name') }}">
                            <span class="text-danger">
                                <strong id="create-name-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createPermission">Permessi: </label>
                            <select id="createPermission" class="selectpicker show-tick form-control border" multiple data-live-search="true" data-style="btn-white">
                                <option value=""></option>
                                @foreach($permissions as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
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
    $("#createRuoloForm").submit(function(e){
        e.preventDefault();

        let name = $("#createName").val();
        let permissions = $("#createPermission").val();

        $("#create-name-error").html("");

        $.ajax({
            url: "{{ route('role.store') }}",
            type: 'POST',
            data: {
                name: name,
                permission: permissions,
            },
            success: function(response) {
                if (response.errors) {
                    $('#createErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#createErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('role.show') }}"
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

    $("#createRuoloModal").on('hidden.bs.modal', function () {
        $("#create-name-error").html("");
        $("#createErrorLabel").fadeOut();
    })
</script>
