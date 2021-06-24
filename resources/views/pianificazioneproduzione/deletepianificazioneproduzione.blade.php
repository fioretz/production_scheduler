{{-- Delete Pianificazione Produzione --}}
<div class="modal fade" id="deletePianificazioneProduzioneModal" tabindex="-1" aria-labelledby="deletePianificazioneProduzioneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePianificazioneProduzioneModalLabel">Elimina Pianificazione Produzione</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deletePianificazioneProduzioneForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questa Pianificazione di Produzione?</p>
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
    function deletePianificazioneById() {
        let id = $("#pianificazione").val();
        if (_.isEmpty(id)) {
            $('#select-pianificazione-error').html('<span style="color: #7f0d0d;">Selezionare una pianificazione di produzione</span>');
            return;
        }
        $("#deletePianificazioneProduzioneModal").modal('toggle');
    }

    $("#deletePianificazioneProduzioneForm").submit(function(e) {
        e.preventDefault();

        let id = $("#pianificazione").val();

        $.ajax({
            url: '/pianificazioneproduzione/' + id + '/delete',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('pianificazioneproduzione.show') }}"
            }
        })
    });
</script>
