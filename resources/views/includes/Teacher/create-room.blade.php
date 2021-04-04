<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="newRoom">
                    @csrf
                    <div class="form-group">
                        <label for="input-new"><b>Room Name</b></label>
                        <input id="input-new" autocomplete="off" value="{{ old('name') }}" type="text" name="name" class="form-control" style="text-transform:uppercase" />
                        <div class="error"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary">Save</button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("input[name='name']").on({
        keydown: function(e) {
            if (e.which === 32)
                return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });


    $('button[type="submit"]').click(event => {
        event.preventDefault();
        let data = formToJSON($('form[name="newRoom"]'));
        ajaxOnLoad("{{ route('rooms.store') }}", 'POST', data, function (res) {
            $('.loader').addClass('hide-load');
            if (res.error){
                $('input[name="name"]').addClass('is-invalid');
                let template = `
                    <strong class="text-danger" style="font-size:12px">${res.error}</strong>
                `;
                $('.error').html(template);
                return null;
            }
            $('.modal').modal('hide');
            location.reload();
        }, function (res) {

        });
    })

</script>
