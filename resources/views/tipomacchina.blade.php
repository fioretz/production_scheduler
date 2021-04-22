@extends('layouts.app')
@section('title')
    Tipo Macchina
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Tipo Macchina</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createTipoMacchinaModal">Nuovo Tipo Macchina</a>
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
                <tr id="sid{{ $row->id }}">
                    <td style="vertical-align: middle">{{ $row->codice }}</td>
                    <td style="vertical-align: middle">{{ $row->descrizione }}</td>
                    <td style="vertical-align: middle">
                        <a href="javascript:void(0)" onclick="editTipoMacchina({{ $row->id }})" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
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

    @include('createtipomacchina')
    @include('edittipomacchina')

@endsection
