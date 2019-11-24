<div class="links">
    <a href=/events>События </a> 
    @if((Auth::check()) and (Auth::user()->role == "admin"))
        <a href=/users>Пользователи </a> 
        <a href=/statuses> Статусы </a>
    @endif 
        @guest <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        @if (Route::has('register'))
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
        @else

        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a> {{ Auth::user()->name }} </a>
        @endguest
</div>