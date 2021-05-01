{{-- Delete Prodotto --}}
<div class="modal fade" id="deleteProdottoModal" tabindex="-1" aria-labelledby="deleteProdottoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProdottoModalLabel">Elimina Prodotto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteProdottoForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questo Prodotto?</p>
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
    function deleteProdotto(id) {
        $('#deleteId').val(id);
        $('#deleteProdottoModal').modal('toggle');
    }

    $("#deleteProdottoForm").submit(function(e) {
        e.preventDefault();

        let id = $("#deleteId").val();

        $.ajax({
            url: '/prodotto/' + id + '/delete',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('prodotto.show') }}"
            }
        })
    });
</script>
