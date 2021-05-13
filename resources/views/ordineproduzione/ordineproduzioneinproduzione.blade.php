@extends('layouts.app')
@section('title')
    Ordini Produzione In Produzione
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 mt-5 border-bottom">
        <h2>Ordini di Produzione In Produzione</h2>
    </div>

    @if(Session::has('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('closeError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ Session::get('closeError') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form class="mb-3">
        <div class="form-row">
            <div class="col-3" data-column="1">
                <label for="col1_filter">Numero Ordine:</label>
                <input type="text" id="col1_filter" class="form-control column-filter" placeholder="Numero Ordine">
            </div>
            <div class="col-3" data-column="2">
                <label for="col2_filter">Prodotto:</label>
                <select id="col2_filter" class="selectpicker show-tick form-control column-filter border" data-live-search="true" data-style="btn-white">
                    <option value="">Mostra Tutto</option>
                    @foreach($prodotti as $row)
                        <option value="{{ $row->codice }}">{{ $row->codice }} - {{ $row->descrizione }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <br><br>
    <div class="table-responsive rounded">
        <table id="ordineProduzioneInProduzioneTable" class="table table-striped table-hover display">
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
                        @if ($row->stato == 'P') <a href="javascript:void(0)" onclick="closeOrdineProduzione({{ $row->id }})" class="btn btn-sm btn-success"><i class="fa fa-times-circle"></i></a> @endif
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
            $('#ordineProduzioneInProduzioneTable').DataTable({
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
            $('#ordineProduzioneInProduzioneTable').DataTable().column(i).search(
                $('#col'+i+'_filter').val(),
            ).draw();
        }
    </script>

    @include('ordineproduzione.closeordineproduzione')

@endsection
