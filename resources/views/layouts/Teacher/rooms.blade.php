@extends('layouts.app')
@section('navbar')
    @include('.includes.Teacher.navbar')
@endsection
@section('content')
    <div class="d-flex justify-content-between">
        <h1 class="text-secondary">Rooms</h1>
        <div>
            <button name="newRoom" class="btn btn-outline-secondary"> <i class="fas fa-plus"></i> ADD ROOM</button>
        </div>
    </div>
    <div class="content my-2">
        <form action="{{ route('rooms.index') }}" method="GET" style="width: 30%"><input type="text" class="form-control text-uppercase" name="s" placeholder="search.." value="{{ $s }}"></form>
        @if(count($rooms) == 0)
            <p class="text-secondary text-lg-center">Don't have any room</p>
        @else
            <table class="table mt-4">
                <caption>List rooms</caption>
                <thead>
                <tr>
                    <th style="width: 10%" class="text-secondary text-sm-left" scope="col"><small class="text-uppercase">Upload</small></th>
                    <th style="width: 15%" class="text-secondary text-center" scope="col"><small class="text-uppercase">status</small></th>
                    <th style="width: 50%" class="text-secondary" colspan="3" scope="col"><small class="text-uppercase">room name</small></th>
                    <th style="" class="text-secondary text-center" scope="col"><small class="text-uppercase">share</small></th>
                    <th style="" class="text-secondary text-center" scope="col"><small class="text-uppercase">delete</small></th>
                </tr>
                </thead>
                <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <th scope="row">
                            <input item-id="{{ $room['id'] }}"  name="status" type="checkbox" @if($room['status'] == 1) checked @endif>
                        </th>
                        <th class="text-center">
                            @if($room['status'] == 1)
                                <i class="fa fa-wifi blink" aria-hidden="true"></i>
                            @endif
                        </th>
                        <th colspan="3">
                            <span style="font-size:18px;" class="text-dark text-uppercase">{{ $room['name'] }}</span>
                            <small class="px-2 text-secondary pull-right">modified {{ \Illuminate\Support\Carbon::parse($room['updated_at'])->diffForHumans() }}</small>
                        </th>
                        <th style="padding: 0;" class="text-center">
                            @if($room['status'] == 1)
                                <i name="share" class="fas fa-share-alt btn-awesome btn-share"></i>
                            @endif
                        </th>
                        <th style="padding: 0;" class="text-center">
                            <i name="delete" status="{{ $room['status'] }}" item-id="{{ $room['id'] }}" class="fa fa-trash-alt btn-awesome btn-trash"></i>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pull-right">{{ $rooms->links() }}</div>
        @endif
    </div>
<script type="text/javascript">
    let btn_add_room = $('button[name="newRoom"]');
    $(btn_add_room).click( e => {
        e.preventDefault();
        ajaxOnLoad("{{ route('rooms.create') }}", "GET", null, res => {
            loadModal(res);
        }, function (res) {
            showError(res.error);
        });
    });

    let share_room = $('i[name="share"]');
    $(share_room).click(event => {

    })

    let delete_room = $('i[name="delete"]');
    $(delete_room).click(event => {
        if ($(event.target).attr('status') == 1){
            $.alert({
                title: "Can't not delete",
                content: "You must setup offline for this room before deleting"
            })
            return null;
        }
        showConfirm("Are you sure want to delete this room?", function () {
            let uri = "{{ route('rooms.update', ":id") }}";
            uri = uri.replace(":id", $(event.target).attr('item-id'));
            ajaxOnLoad(uri, 'DELETE', null, res => {
                location.reload();
            });
        }, function () {

        });
    });

    $('input[name="status"]').change(e => {
        let uri = "{{ route('rooms.update', ":id") }}";
        uri = uri.replace(":id", $(e.target).attr('item-id'));
        ajaxOnLoad(uri, "PUT", null, res => {
            if (res.success === true)
                location.reload();
        }, res => {
            $('.loader').addClass('hide-load');
            showError("Something wrong happen", function () {
                location.reload();
            });
        });
    });

    $(document).ready(function() {
    })
</script>
@endsection
