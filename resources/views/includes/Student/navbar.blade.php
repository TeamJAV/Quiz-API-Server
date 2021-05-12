<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="display:flex;flex-direction:column;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/web/puzzle-piece.svg') }}" alt="logo" width="50">
        </a>
        <p class="display-4 text-uppercase">{{ \Illuminate\Support\Facades\Session::get('room')['name'] }}</p>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav">

            <!-- Authentication Links -->
            @if(\Illuminate\Support\Facades\Session::has('student'))
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ \Illuminate\Support\Facades\Session::get('student')['name'] }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('student.logout.room') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
@if(\Illuminate\Support\Facades\Session::has('room') && \Illuminate\Support\Facades\Session::has('student'))
<script type="text/javascript">

    const bindURL = data => {
        let room_id = "{{ \Illuminate\Support\Facades\Session::get('room')['id'] }}";
        if (room_id == data.room_id && data.online === true){
            location.href = "{{ route('student.join', \Illuminate\Support\Facades\Session::get('room')['id']) }}";
        }
        if (room_id == data.room_id && data.online === false){
            location.href = "{{ route('student.wait') }}";
        }
    }

    $(document).ready(function() {
        let channel = pusher.subscribe('room-online');
        channel.bind("App\\Events\\RoomOnlineEvent", bindURL);
        // Echo.private('room-online').listen("RoomOnlineEvent", e => bindURL())

    });
</script>
@endif
