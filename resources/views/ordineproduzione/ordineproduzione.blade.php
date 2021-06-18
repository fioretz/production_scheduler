@extends('layouts.app')
@section('title')
    Ordini Produzione
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Ordini di Produzione</h2>
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
                <label for="col2_filter">Numero Ordine:</label>
                <input type="text" id="col2_filter" class="form-control column-filter" placeholder="Numero Ordine">
            </div>
            <div class="col-3" data-column="3">
                <label for="col3_filter">Prodotto:</label>
                <select id="col3_filter" class="selectpicker show-tick form-control column-filter border" data-live-search="true" data-style="btn-white">
                    <option value="">Mostra Tutto</option>
                    @foreach($prodotti as $row)
                        <option value="{{ $row->codice }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="btn-toolbar mb-2 mb-md-0 float-right">
        <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createOrdineProduzioneModal">Nuovo Ordine Produzione</a>
    </div>
    <div class="table-responsive rounded">
        <table id="ordineProduzioneTable" class="table table-striped table-hover display" style="width: 100%">
            <thead>
            <tr>
                <th hidden></th> {{-- aggiunto per poter togliere l'ordinamento dalla prima colonna --}}
                <th></th>
                <th>Numero Ordine</th>
                <th>Prodotto</th>
                <th>Quantità</th>
                <th>Tempo Produzione</th>
                <th>Data Scadenza</th>
                <th>Data Fine</th>
                <th class="text-center">Stato</th>
            </tr>
            </thead>
            <tbody>
            @foreach($ordiniproduzione as $row)
                <tr id="sid{{ $row->id }}">
                    <td hidden></td>
                    <td style="vertical-align: middle">
                        <a href="javascript:void(0)" onclick="editOrdineProduzione({{ $row->id }})" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                        @if ($row->stato == 'A') <a href="javascript:void(0)" onclick="deleteOrdineProduzione({{ $row->id }})" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a> @endif
                    </td>
                    <td style="vertical-align: middle">{{ $row->numeroordine }}</td>
                    <td style="vertical-align: middle">{{ $row->prodotto_codice }}- {{ $row->prodotto_descrizione }}</td>
                    <td style="vertical-align: middle">{{ $row->ordineproduzione_quantita }}</td>
                    <td style="vertical-align: middle">{{ $row->ore }} h {{ $row->minuti }} m {{ $row->secondi }} s</td>
                    <td style="vertical-align: middle">@if (!empty($row->datascadenza)) {{ date('d/m/Y', strtotime($row->datascadenza)) }} @endif</td>
                    <td style="vertical-align: middle">@if (!empty($row->datafine)) {{ date('d/m/Y', strtotime($row->datafine)) }} @endif</td>
                    <td class="text-center" style="vertical-align: middle">{{ $row->stato }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready( function () {
            $('#ordineProduzioneTable').DataTable({
                'oder': [],
                'pageLength': 10,
                'language': {
                    'lenghtMenu': 'Mostra _MENU_ ordini per pagina',
                    'zeroRecords': 'Non ci sono ordini',
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
                    { 'width': '20%', 'orderable': true },
                    { 'width': '12%', 'orderable': false },
                    { 'width': '14%', 'orderable': false },
                    { 'width': '12%', 'orderable': true },
                    { 'width': '12%', 'orderable': true },
                    { 'width': '5%', 'orderable': false },
                ],
            });

            $('input.column-filter').on('keyup click', function() {
                filterOrdineProduzione($(this).parents('div').attr('data-column'));
            });

            $('select.column-filter').on('change', function() {
                filterOrdineProduzione($(this).parents().parents().attr('data-column'));
            })
        });

        function filterOrdineProduzione(i) {
            $('#ordineProduzioneTable').DataTable().column(i).search(
                $('#col'+i+'_filter').val(),
            ).draw();
        }
    </script>

    @include('ordineproduzione.createordineproduzione')
    @include('ordineproduzione.editordineproduzione')
    @include('ordineproduzione.deleteordineproduzione')

@endsection
