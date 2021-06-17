{{-- Create Utente --}}
<div class="modal fade" id="createUtenteModal" tabindex="-1" aria-labelledby="createUtenteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUtenteModalLabel">Nuovo utente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createUtenteForm">
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
                            <label for="createEmail">E-mail: </label>
                            <input type="text" name="createEmail" id="createEmail" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email') }}">
                            <span class="text-danger">
                                <strong id="create-email-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createPassword">Password: </label>
                            <input type="password" name="createPassword" id="createPassword" class="form-control @if($errors->has('password')) is-invalid @endif" value="{{ old('password') }}">
                            <span class="text-danger">
                                <strong id="create-password-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createConfirmPassword">Conferma Password: </label>
                            <input type="password" name="createConfirmPassword" id="createConfirmPassword" class="form-control @if($errors->has('password')) is-invalid @endif" value="{{ old('password') }}">
                            <span class="text-danger">
                                <strong id="create-confirm-password-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="createRoles">Ruoli: </label>
                            <select id="createRoles" class="selectpicker show-tick form-control border" multiple data-live-search="true" data-style="btn-white">
                                <option value=""></option>
                                @foreach($roles as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">
                                <strong id="create-roles-error"></strong>
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
    $("#createUtenteForm").submit(function(e){
        e.preventDefault();

        let name = $("#createName").val();
        let email = $("#createEmail").val();
        let password = $("#createPassword").val();
        let confirm_password = $("#createConfirmPassword").val();
        let roles = $("#createRoles").val();

        $("#create-name-error").html("");
        $("#create-email-error").html("");
        $("#create-password-error").html("");
        $("#create-confirm-password-error").html("");

        $.ajax({
            url: "{{ route('user.store') }}",
            type: 'POST',
            data: {
                name: name,
                email: email,
                password: password,
                confirm_password: confirm_password,
                roles: roles,
            },
            success: function(response) {
                if (response.errors) {
                    $('#createErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#createErrorLabel\').fadeOut()">&times;</button>'
                    ).fadeIn();
                } else {
                    window.location.href = "{{ route('user.show') }}"
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

    $("#createUtenteModal").on('hidden.bs.modal', function () {
        $("#create-name-error").html("");
        $("#create-email-error").html("");
        $("#create-password-error").html("");
        $("#create-confirm-password-error").html("");
        $("#createErrorLabel").fadeOut();
    })
</script>
