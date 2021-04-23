@extends('layouts.app')
@section('title')
    Tipo Macchina
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Tipo Macchina</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form class="mb-3">
        <div class="form-row">
            <div class="col-3" data-column="2">
                <label for="col2_filter">Codice:</label>
                <input type="text" id="col2_filter" class="form-control column-filter" placeholder="Codice">
            </div>
            <div class="col-3" data-column="3">
                <label for="col3_filter">Codice:</label>
                <input type="text" id="col3_filter" class="form-control column-filter" placeholder="Descrizione">
            </div>
        </div>
    </form>

    <div class="btn-toolbar mb-2 mb-md-0 float-right">
        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createTipoMacchinaModal">Nuovo Tipo Macchina</a>
    </div>
    <div class="table-responsive rounded">
        <table id="tipoMacchinaTable" class="table table-striped table-hover display">
            <thead>
                <tr>
                    <th hidden></th> {{-- aggiunto per poter togliere l'ordinamento dalla prima colonna --}}
                    <th></th>
                    <th>Codice</th>
                    <th>Descrizione</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                    <tr id="sid{{ $row->id }}">
                        <td hidden></td>
                        <td style="vertical-align: middle">
                            <a href="javascript:void(0)" onclick="editTipoMacchina({{ $row->id }})" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0)" onclick="deleteTipoMacchina({{ $row->id }})" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                        <td style="vertical-align: middle">{{ $row->codice }}</td>
                        <td style="vertical-align: middle">{{ $row->descrizione }}</td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready( function () {
            $('#tipoMacchinaTable').DataTable({
                'oder': [],
                'pageLength': 10,
                'language': {
                    'lenghtMenu': 'Mostra _MENU_ tipi macchina per pagina',
                    'zeroRecords': 'Non ci sono tipi macchina',
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
                    { 'width': '20%', 'orderable': true },
                    { 'width': '70%', 'orderable': true },
                ],
            });

            $('input.column-filter').on('keyup click', function() {
                filterTipoMacchina($(this).parents('div').attr('data-column'));
            })
        });

        function filterTipoMacchina(i) {
            $('#tipoMacchinaTable').DataTable().column(i).search(
                $('#col'+i+'_filter').val(),
            ).draw();
        }
    </script>

    @include('tipomacchina.createtipomacchina')
    @include('tipomacchina.edittipomacchina')
    @include('tipomacchina.deletetipomacchina')

@endsection
