@extends('layouts.app')
@section('title')
    Macchine
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Macchine</h2>
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
                <label for="col3_filter">Descrizione:</label>
                <input type="text" id="col3_filter" class="form-control column-filter" placeholder="Descrizione">
            </div>
            <div class="col-3" data-column="4">
                <label for="col4_filter">Tipo Macchina:</label>
                <select id="col4_filter" class="selectpicker show-tick form-control column-filter border" data-live-search="true" data-style="btn-white">
                    <option value="">Mostra Tutto</option>
                    @foreach($tipomacchina as $row)
                        <option value="{{ $row->codice }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="btn-toolbar mb-2 mb-md-0 float-right">
        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createMacchinaModal">Nuova Macchina</a>
    </div>
    <div class="table-responsive rounded">
        <table id="macchinaTable" class="table table-striped table-hover display">
            <thead>
            <tr>
                <th hidden></th> {{-- aggiunto per poter togliere l'ordinamento dalla prima colonna --}}
                <th></th>
                <th>Codice</th>
                <th>Descrizione</th>
                <th>Tipo Macchina</th>
            </tr>
            </thead>
            <tbody>
            @foreach($macchine as $row)
                <tr id="sid{{ $row->id }}">
                    <td hidden></td>
                    <td style="vertical-align: middle">
                        <a href="javascript:void(0)" onclick="editMacchina({{ $row->id }})" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="deleteMacchina({{ $row->id }})" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                    <td style="vertical-align: middle">{{ $row->macchina_codice }}</td>
                    <td style="vertical-align: middle">{{ $row->macchina_descrizione }}</td>
                    <td style="vertical-align: middle">{{ $row->tipomacchina_codice }} - {{ $row->tipomacchina_descrizione }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready( function () {
            $('#macchinaTable').DataTable({
                'oder': [],
                'pageLength': 10,
                'language': {
                    'lenghtMenu': 'Mostra _MENU_ macchine per pagina',
                    'zeroRecords': 'Non ci sono macchine',
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
                    { 'width': '15%', 'orderable': true },
                    { 'width': '30%', 'orderable': true },
                    { 'width': '45%', 'orderable': true },
                ],
            });

            $('input.column-filter').on('keyup click', function() {
                filterMacchina($(this).parents('div').attr('data-column'));
            });

            $('select.column-filter').on('change', function() {
                filterMacchina($(this).parents().parents().attr('data-column'));
            })
        });

        function filterMacchina(i) {
            $('#macchinaTable').DataTable().column(i).search(
                $('#col'+i+'_filter').val(),
            ).draw();
        }
    </script>

    @include('macchina.createmacchina')
    @include('macchina.editmacchina')
    @include('macchina.deletemacchina')

@endsection
