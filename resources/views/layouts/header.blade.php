<div class="links">
    <a href=/events?status_id=0>События </a> 
    @if(Auth::check())
        <a href=/events?user_id={{Auth::user() -> id }}>Мои события</a> 
        <a href=/users/user/{{Auth::user()->id}}>Мой профиль</a>
            @if(App\User::selectAuthUser()->levelRights > 1)                
                <a href=/users>Пользователи </a> 
                <a href=/statuses> Статусы </a>
                <a href=/categories>Категории </a>
                <a href=/deleteImagesWithoutLink> Удалить неиспользуемые изображения </a>
            @endif
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