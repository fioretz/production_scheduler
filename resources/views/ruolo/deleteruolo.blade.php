{{-- Delete Ruolo --}}
<div class="modal fade" id="deleteRuoloModal" tabindex="-1" aria-labelledby="deleteRuoloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRuoloModalLabel">Elimina Ruolo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Chiudi">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteRuoloForm">
                <div class="modal-body">
                    <input type="hidden" id="deleteId" name="deleteId" />
                    <p>Sei sicuro di voler eliminare questo Ruolo?</p>
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
    function deleteRuolo(id) {
        $('#deleteId').val(id);
        $('#deleteRuoloModal').modal('toggle');
    }

    $("#deleteRuoloForm").submit(function(e) {
        e.preventDefault();

        let id = $("#deleteId").val();

        $.ajax({
            url: '/role/' + id + '/delete',
            type: 'POST',
            success: function(response) {
                window.location.href = "{{ route('role.show') }}"
            }
        })
    });
</script>
