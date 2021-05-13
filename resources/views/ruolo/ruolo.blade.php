@extends('layouts.app')
@section('title')
    Ruoli
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Ruoli</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('deleteError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('deleteError') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form class="mb-3">
        <div class="form-row">
            <div class="col-3" data-column="2">
                <label for="col2_filter">Nome:</label>
                <input type="text" id="col2_filter" class="form-control column-filter" placeholder="Codice">
            </div>
        </div>
    </form>

    <div class="btn-toolbar mb-2 mb-md-0 float-right">
        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createRuoloModal">Nuovo Ruolo</a>
    </div>
    <div class="table-responsive rounded">
        <table id="ruoloTable" class="table table-striped table-hover display">
            <thead>
            <tr>
                <th hidden></th> {{-- aggiunto per poter togliere l'ordinamento dalla prima colonna --}}
                <th></th>
                <th>Nome</th>
                <th>Permessi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $row)
                <tr id="sid{{ $row->id }}">
                    <td hidden></td>
                    <td style="vertical-align: middle">
                        <a href="javascript:void(0)" onclick="editRuolo({{ $row->id }})" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="deleteRuolo({{ $row->id }})" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                    <td style="vertical-align: middle">{{ $row->name }}</td>
                    <td style="vertical-align: middle">
                        @if(!empty($row->getPermissionNames()))
                            @foreach($row->getPermissionNames() as $permission)
                                <label class="badge badge-success">{{ $permission }}</label>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready( function () {
            $('#ruoloTable').DataTable({
                'oder': [],
                'pageLength': 10,
                'language': {
                    'lenghtMenu': 'Mostra _MENU_ ruoli per pagina',
                    'zeroRecords': 'Non ci sono ruoli',
                    'info': 'Mostra pagina _PAGE_ di _PAGES_ ',
                    'infoFiltered': '(filtrati su _MAX_ elementi totali)',
                    'infoEmpty': 'Mostra 0 a 0 di 0 elementi',
                    'search': 'Cerca: ',
                    'paginate': {
                        'next': 'Prossimo',
                        'previous': 'Precedente'
                    }
                },
                'dom': 'Brtip',
                'columns': [
                    { 'width': '0%', 'orderable': true },
                    { 'width': '10%', 'orderable': false },
                    { 'width': '15%', 'orderable': false },
                    { 'width': '75%', 'orderable': false },
                ],
            });

            $('input.column-filter').on('keyup click', function() {
                filterRuolo($(this).parents('div').attr('data-column'));
            });
        });

        function filterRuolo(i) {
            $('#ruoloTable').DataTable().column(i).search(
                $('#col'+i+'_filter').val(),
            ).draw();
        }
    </script>

    @include('ruolo.createruolo')
    @include('ruolo.editruolo')
    @include('ruolo.deleteruolo')

@endsection
