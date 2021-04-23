{{-- Delete Tipo Macchina --}}
<div class="modal fade" id="deleteTipoMacchinaModal" tabindex="-1" aria-labelledby="deleteTipoMacchinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTipoMacchinaModalLabel">Elimina Tipo Macchina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteTipoMacchinaForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questo tipo macchina?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function deleteTipoMacchina(id) {
        $('#deleteId').val(id);
        $('#deleteTipoMacchinaModal').modal('toggle');
    }

    $("#deleteTipoMacchinaForm").submit(function(e) {
        e.preventDefault();

        let id = $("#deleteId").val();

        $.ajax({
            url: '/tipomacchina/' + id + '/delete',
            type: 'POST',
            data:{
                _token: $("input[name=_token]").val()
            },
            success: function(response) {
                window.location.href = "{{ route('tipomacchina.show') }}"
            }
        })
    });
</script>
