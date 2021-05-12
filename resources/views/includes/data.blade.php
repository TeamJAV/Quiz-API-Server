@foreach($rooms as $room)
    <tr>
        <th scope="row">
            <input type="checkbox" @if($room['status'] == 1) checked @endif data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="xs" name="status" item-id="{{ $room['id'] }}">
        </th>
        <th class="text-center">
                                            <i class="fa fa-wifi blink" aria-hidden="true"></i>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle @if($room['status'] == 1) text-success  @else text-danger @endif" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
            </svg>
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
