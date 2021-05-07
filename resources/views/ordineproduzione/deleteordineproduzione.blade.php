{{-- Delete Ordine Produzione --}}
<div class="modal fade" id="deleteOrdineProduzioneModal" tabindex="-1" aria-labelledby="deleteOrdineProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOrdineProduzioneModalLabel">Elimina Ordine Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteOrdineProduzioneForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questo Ordine di Produzione?</p>
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
    function deleteOrdineProduzione(id) {
        $('#deleteId').val(id);
        $('#deleteOrdineProduzioneModal').modal('toggle');
    }

    $("#deleteOrdineProduzioneForm").submit(function(e) {
        e.preventDefault();

        let id = $("#deleteId").val();

        $.ajax({
            url: '/ordineproduzione/' + id + '/delete',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('ordineproduzione.show') }}"
            }
        })
    });
</script>
