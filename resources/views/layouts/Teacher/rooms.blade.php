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
    <div class="content my-2 infinite-scroll">
        <form action="{{ route('rooms.index') }}" method="GET" style="width: 30%"><input type="text" style="font-size:13px!important;" class="form-control text-uppercase" name="s" placeholder="search.." value="{{ $s }}"></form>
        @if(count($rooms) == 0)
            <p class="text-secondary text-lg-center">Don't have any room</p>
        @else
            <table class="table mt-4">
                <thead>
                <tr>
                    <th style="width: 5%" class="text-secondary text-sm-left" scope="col"><small class="text-uppercase">#</small></th>
                    <th style="width: 15%" class="text-secondary text-center" scope="col"><small class="text-uppercase">status</small></th>
                    <th style="width: 40%" class="text-secondary" colspan="3" scope="col"><small class="text-uppercase">room name</small></th>
                    <th style="width: 10%" class="text-secondary text-center" scope="col"><small class="text-uppercase">Join</small></th>
                    <th style="width: 10%" class="text-secondary text-center" scope="col"><small class="text-uppercase">Time remaining</small></th>
                    <th style="width: 5%" class="text-secondary text-center" scope="col"><small class="text-uppercase">delete</small></th>
                </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $index => $room)
                            <tr>
                                <th scope="row">
{{--                                    <input type="checkbox" @if($room['status'] == 1) checked @endif data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="xs" name="status" item-id="{{ $room['id'] }}">--}}
                                    <small>{{ $index + 1 }}</small>
                                </th>
                                <th class="text-center">
                                    {{--                                    <i class="fa fa-wifi blink" aria-hidden="true"></i>--}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle @if($room['status'] == 1) text-success  @else text-danger @endif" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                    </svg>
                                </th>
                                <th colspan="3">
                                    <span style="font-size:16px;" class="text-dark text-uppercase">{{ $room['name'] }}</span>
                                    <i class="fa fa-pencil px-4 text-secondary" onclick="updateRoom(event, {{ $room['id'] }})" aria-hidden="true"></i>
                                </th>
                                <th style="padding: 0;" class="text-center">
                                    @if($room['status'] == 1)
                                        <i class="fa fa-user-o btn-awesome btn-share" aria-hidden="true">
                                            <span class="badge badge-danger">13</span>
                                        </i>
                                    @endif
                                </th>
                                <th class="text-center">
                                    @if($room['status'] == 1)
                                        <i class="fas fa-infinity text-secondary"></i>
                                    @endif
                                </th>
                                <th style="padding: 0;" class="text-center">
                                    <i name="delete" status="{{ $room['status'] }}" item-id="{{ $room['id'] }}" class="fa fa-trash-alt btn-awesome btn-trash"></i>
                                </th>
                            </tr>
                    @endforeach
{{--                    @include(".includes.data")--}}
                </tbody>
            </table>
            <div class="pull-right">
                {{ $rooms->links() }}
            </div>
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


    const updateRoom = (event, id) => {
        event.preventDefault();
        let url = "{{ route('rooms.edit', ":id") }}";
        url = url.replace(":id", id);
        ajaxOnLoad(url, "GET", null, res => {
            loadModal(res);
        }, function (res) {
            showError(res.error);
        });
    }

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

</script>
@endsection
