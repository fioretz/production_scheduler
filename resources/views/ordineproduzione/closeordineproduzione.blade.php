{{-- Close Ordine Produzione --}}
<div class="modal fade" id="closeOrdineProduzioneModal" tabindex="-1" aria-labelledby="closeOrdineProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeOrdineProduzioneModalLabel">Chiudi Ordine Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="closeOrdineProduzioneForm">
                <div class="modal-body">
                    <input type="hidden" id="closeId" name="closeId" />
                    <p>Sei sicuro di voler chiudere questo Ordine di Produzione?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancella</button>
                    <button type="submit" class="btn btn-success">Chiudi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeOrdineProduzione(id) {
        $('#closeId').val(id);
        $('#closeOrdineProduzioneModal').modal('toggle');
    }

    $("#closeOrdineProduzioneForm").submit(function(e) {
        e.preventDefault();

        let id = $("#closeId").val();

        $.ajax({
            url: '/ordineproduzione/' + id + '/close',
            type: 'GET',
            success: function(response) {
                window.location.href = "{{ route('ordineproduzione.showordiniinproduzione') }}"
            }
        })
    });
</script>
