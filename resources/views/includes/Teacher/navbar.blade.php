<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="display:flex;flex-direction:column; padding-bottom: 0;">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <p class="display-4 text-uppercase">{{ auth()->user()->name }}</p>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
    </div>
    <div class="container mt-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent" style="flex-grow: 0">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-4">
                    <a class="nav-link" href="javascript:void(0)">LAUNCH</a>
                </li>
                <li class="nav-item mx-4">
                    <a class="nav-link" href="javascript:void(0)">QUIZZES</a>
                </li>
                <li class="nav-item mx-4">
                    <a class="nav-link" href="javascript:void(0)">ROOMS</a>
                </li>
                <li class="nav-item mx-4">
                    <a class="nav-link" href="javascript:void(0)">REPORTS</a>
                </li>
                <li class="nav-item mx-4">
                    <a class="nav-link" href="javascript:void(0)">RESULT</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
