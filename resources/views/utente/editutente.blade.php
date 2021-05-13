{{-- Edit Utente --}}
<div class="modal fade" id="editUtenteModal" tabindex="-1" aria-labelledby="editUtenteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUtenteModalLabel">Modifica Utente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUtenteForm">
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
                            <label for="editEmail">E-mail: </label>
                            <input type="text" name="editEmail" id="editEmail" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email') }}">
                            <span class="text-danger">
                                    <strong id="edit-email-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editPassword">Password: </label>
                            <input type="password" name="editPassword" id="editPassword" class="form-control @if($errors->has('password')) is-invalid @endif" value="{{ old('password') }}">
                            <span class="text-danger">
                                    <strong id="edit-password-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editConfirmPassword">Conferma Password: </label>
                            <input type="password" name="editConfirmPassword" id="editConfirmPassword" class="form-control @if($errors->has('password')) is-invalid @endif" value="{{ old('password') }}">
                            <span class="text-danger">
                                    <strong id="edit-confirm-password-error"></strong>
                                </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="editRoles">Ruoli: </label>
                            <select id="editRoles" class="selectpicker show-tick form-control border" multiple data-live-search="true" data-style="btn-white">
                                @foreach($roles as $row)
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
    function editUtente(id)
    {
        $.get('/user/'+id, function(data) {
            $('#editId').val(data.user.id);
            $('#editName').val(data.user.name);
            $('#editEmail').val(data.user.email);
            $('#editPassword').val(data.user.password);
            $('#editConfirmPassword').val(data.user.password);
            let roles = [];
            _.each(data.editRoles, function(role) {
                roles.push(role)
            });
            $('#editRoles').selectpicker('val', roles);
            $('#editUtenteModal').modal('toggle');
        })
    }

    $("#editUtenteForm").submit(function(e){
        e.preventDefault();

        let id = $("#editId").val();
        let name = $("#editName").val();
        let email = $("#editEmail").val();
        let password = $("#editPassword").val();
        let confirm_password = $("#editConfirmPassword").val();
        let roles = $("#editRoles").val();

        $("#edit-name-error").html("");
        $("#edit-email-error").html("");
        $("#edit-password-error").html("");
        $("#edit-confirm-password-error").html("");

        $.ajax({
            url: "{{ route('user.update') }}",
            type: 'POST',
            data: {
                id: id,
                name: name,
                email: email,
                password: password,
                confirm_password: confirm_password,
                roles: roles,
            },
            success: function(response) {
                if (response.errors) {
                    $('#editErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#editErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('user.show') }}"
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

    $("#editUtenteModal").on('hidden.bs.modal', function () {
        $("#edit-name-error").html("");
        $("#edit-email-error").html("");
        $("#edit-password-error").html("");
        $("#edit-confirm-password-error").html("");
        $('#editErrorLabel').fadeOut();
    })
</script>
