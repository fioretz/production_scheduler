@extends('layouts.app')
@section('title')
    Tipo Macchina
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Tipo Macchina</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tipoMacchinaModal">Nuovo Tipo Macchina</a>
        </div>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="table-responsive shadow rounded">
        <table id="tipoMacchinaTable" class="table table-striped table-hover">
            <tr>
                <th>Codice</th>
                <th>Descrizione</th>
                <th></th>
            </tr>
            @foreach($data as $row)
                <tr>
                    <td style="vertical-align: middle">{{ $row->codice }}</td>
                    <td style="vertical-align: middle">{{ $row->descrizione }}</td>
                    <td style="vertical-align: middle">
                        <a href="{{route('tipomacchina.edit',[ 'tipomacchina'=>$row ] )}}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <a href="{{route('tipomacchina.delete', [ 'tipomacchina'=>$row ])}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </table>
        <nav aria-label="Pagination" class="d-flex justify-content-between align-items-center px-2">
            <p>{{ $data->firstItem() }} - {{ $data->lastItem() }} di {{ $data->total() }}</p>
            <ul class="pagination justify-content-center">
                <form class="form-inline mr-1" method="GET" action="" role="form">
                    <div class="form-group">
                        <label for="perPage">Elementi per pagina</label>
                        <select name="perPage" id="perPage" class="form-control ml-1" onchange="this.form.submit()">
                            <option value="5" @if($data->perPage() == 5) selected  @endif>5</option>
                            <option value="10" @if($data->perPage() == 10) selected  @endif>10</option>
                            <option value="15" @if($data->perPage() == 15) selected  @endif>15</option>
                        </select>
                    </div>
                </form>
                {{ $data->appends(['perPage'=>$data->perPage()])->links('pagination::bootstrap-4') }}
            </ul>
        </nav>

    </div>

    <div class="modal fade" id="tipoMacchinaModal" tabindex="-1" aria-labelledby="tipoMacchinaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tipoMacchinaModalLabel">Nuovo Tipo Macchina</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="tipoMacchinaForm">
                    <div class="modal-body">
                        <div id="errorLabel" class="alert alert-danger" style="display: none" role="alert">

                        </div>
                        @csrf
                        <div class="row form-group">
                            <div class="col-12">
                                <label for="codice">Codice: </label>
                                <input type="text" name="codice" id="codice" class="form-control @if($errors->has('codice')) is-invalid @endif" value="{{ old('codice') }}">
                                <span class="text-danger">
                                    <strong id="codice-error"></strong>
                                </span>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <label for="descrizione">Descrizione: </label>
                                <input type="text" name="descrizione" id="descrizione" class="form-control @if($errors->has('descrizione')) is-invalid @endif" value="{{ old('descrizione') }}">
                                <span class="text-danger">
                                    <strong id="descrizione-error"></strong>
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
        $("#tipoMacchinaForm").submit(function(e){
            e.preventDefault();

            let codice = $("#codice").val();
            let descrizione = $("#descrizione").val();
            let _token = $("input[name=_token]").val();

            $("#codice-error").html("");
            $("#descrizione-error").html("");

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
                        $('#errorLabel').html('<strong>Errore! </strong>' + response.errors + '<button type="button" class="close" onclick="$(\'#errorLabel\').fadeOut()">&times;</button>'
                        ).fadeIn();
                    } else {
                        window.location.href = "{{ route('tipomacchina.show') }}"
                    }
                },
                error: function (err) {
                    if (err.status === 422) {
                        $.each(err.responseJSON.errors, function (i, error) {
                            $('#'+i+'-error').html('<span style="color: #7f0d0d;">'+error[0]+'</span>');
                        });
                    }
                }
            })
        });

    </script>
@endsection
