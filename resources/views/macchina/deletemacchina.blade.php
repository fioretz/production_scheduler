{{-- Delete Tipo Macchina --}}
<div class="modal fade" id="deleteMacchinaModal" tabindex="-1" aria-labelledby="deleteMacchinaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMacchinaModalLabel">Elimina Macchina</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteMacchinaForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questa macchina?</p>
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
    function deleteMacchina(id) {
        $('#deleteId').val(id);
        $('#deleteMacchinaModal').modal('toggle');
    }

    $("#deleteMacchinaForm").submit(function(e) {
        e.preventDefault();

        let id = $("#deleteId").val();

        $.ajax({
            url: '/macchina/' + id + '/delete',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('macchina.show') }}"
            }
        })
    });
</script>
