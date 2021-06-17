@extends('layouts.app')
@section('title')
    Profilo
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Profilo</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3">
        <h3>Modifica Nome / Email</h3>
    </div>

    <div id="UpdateNameMaleErrorLabel" class="alert alert-danger" style="display: none" role="alert"></div>
    <form id="updateNameMailForm">
        <div class="form-group col-3">
            <label for="updateName">Nome: </label>
            <input type="text" name="updateName" id="updateName" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ $data->name }}">
            <span class="text-danger">
                <strong id="update-name-error"></strong>
            </span>
        </div>
        <div class="form-group col-3">
            <label for="updateEmail">E-mail: </label>
            <input type="text" name="updateEmail" id="updateEmail" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ $data->email }}">
            <span class="text-danger">
                <strong id="update-email-error"></strong>
            </span>
        </div>
        <div class="col-3">
            <button class="btn btn-primary btn-lg float-right" type="submit">Salva</button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3">
        <h3>Modifica Nome / Email</h3>
    </div>

    <div id="UpdatePasswordErrorLabel" class="alert alert-danger" style="display: none" role="alert"></div>
    <form id="updatePasswordForm">
        <div class="form-group col-3">
            <label for="updatePassword">Password: </label>
            <input type="password" name="updatePassword" id="updatePassword" class="form-control @if($errors->has('password')) is-invalid @endif">
            <span class="text-danger">
                <strong id="update-password-error"></strong>
            </span>
        </div>
        <div class="form-group col-3">
            <label for="updateConfirmPassword">Conferma Password: </label>
            <input type="text" name="updateConfirmPassword" id="updateConfirmPassword" class="form-control @if($errors->has('email')) is-invalid @endif">
            <span class="text-danger">
                <strong id="update-confirm-password-error"></strong>
            </span>
        </div>
        <div class="col-3">
            <button class="btn btn-primary btn-lg float-right" type="submit">Salva</button>
        </div>
    </form>

    <script>
        $("#updateNameMailForm").submit(function(e) {
            e.preventDefault();

            let name = $("#updateName").val();
            let email = $("#updateEmail").val();

            $("#update-name-error").html("");
            $("#update-email-error").html("");

            $.ajax({
                url: "{{ route('profilo.updateNameMail') }}",
                type: 'POST',
                data: {
                    name: name,
                    email: email,
                },
                success: function(response) {
                    if (response.errors) {
                        $('#UpdateNameMaleErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#UpdateNameMaleErrorLabel\').fadeOut()">&times;</button>'
                        ).fadeIn();
                    } else {
                        window.location.href = "{{ route('profilo.show') }}"
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            $('#update-'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                        });
                    }
                }
            })
        });

        $("#updatePasswordForm").submit(function(e) {
            e.preventDefault();

            let password = $("#updatePassword").val();
            let confirm_password = $("#updateConfirmPassword").val();

            $("#update-password-error").html("");
            $("#update-confirm-password-error").html("");

            $.ajax({
                url: "{{ route('profilo.updatePassword') }}",
                type: 'POST',
                data: {
                    password: password,
                    confirm_password: confirm_password,
                },
                success: function(response) {
                    if (response.errors) {
                        $('#UpdatePasswordErrorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#UpdatePasswordErrorLabel\').fadeOut()">&times;</button>'
                        ).fadeIn();
                    } else {
                        window.location.href = "{{ route('profilo.show') }}"
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            $('#update-'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                        });
                    }
                }
            })
        });

    </script>

@endsection


