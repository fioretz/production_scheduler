{{-- Open Ordine Produzione --}}
<div class="modal fade" id="openOrdineProduzioneModal" tabindex="-1" aria-labelledby="openOrdineProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="openOrdineProduzioneModalLabel">Riapri Ordine Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="openOrdineProduzioneForm">
                <div class="modal-body">
                    <input type="hidden" id="openId" name="openId" />
                    <p>Sei sicuro di voler riaprire questo Ordine di Produzione?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                    <button type="submit" class="btn btn-primary">Riapri</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openOrdineProduzione(id) {
        $('#openId').val(id);
        $('#openOrdineProduzioneModal').modal('toggle');
    }

    $("#openOrdineProduzioneForm").submit(function(e) {
        e.preventDefault();

        let id = $("#openId").val();

        $.ajax({
            url: '/ordineproduzione/' + id + '/open',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('ordineproduzione.showOrdiniChiusi') }}"
            }
        })
    });
</script>
