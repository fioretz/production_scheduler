{{-- Edit Ruolo --}}
<div class="modal fade" id="editRuoloModal" tabindex="-1" aria-labelledby="editRuoloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRuoloModalLabel">Modifica Ruolo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRuoloForm">
                <div class="modal-body">
                    <div id="editErrorLabel" class="alert alert-danger" style="display: none" role="alert">
                    </div>
                    @csrf
                    <input type="hidden" id="editId" name="editId" />
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editName">Nome: </label>
                            <input type="text" name="editName" id="editName" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name') }}">
                            <span class="text-danger">
                                    <strong id="edit-name-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editPermission">Permessi: </label>
                            <select id="editPermission" class="selectpicker show-tick form-control border" multiple data-live-search="true" data-style="btn-white">
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

<script>
    function editRuolo(id)
    {
        $.get('/role/'+id, function(data) {
            $('#editId').val(data.role.id);
            $('#editName').val(data.role.name);
            let permissions = [];
            _.each(data.editPermissions, function(permission) {
                permissions.push(permission.id)
            });
            $('#editPermission').selectpicker('val', permissions);
            $('#editRuoloModal').modal('toggle');
        })
    }

    $("#editRuoloForm").submit(function(e){
        e.preventDefault();

        let id = $("#editId").val();
        let name = $("#editName").val();
        let permission = $("#editPermission").val();

        $("#edit-name-error").html("");

        $.ajax({
            url: "{{ route('role.update') }}",
            type: 'POST',
            data: {
                id: id,
                name: name,
                permission: permission,
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('role.show') }}"
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

    $("#editRuoloModal").on('hidden.bs.modal', function () {
        $("#edit-name-error").html("");
        $('#editErrorLabel').fadeOut();
    })
</script>
