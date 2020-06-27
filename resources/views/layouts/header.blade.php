<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="/">На главную</a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/events?status_id=0">События </a>
            </li>
            @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link" href="/events?user_id={{Auth::user() -> id }}">Мои события</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/users/user/{{Auth::user()->id}}">Мои профиль</a>
            </li>
                @if(App\User::selectAuthUser()->levelRights > 1) 
                    <li class="nav-item">
                        <a class="nav-link" href="/users">Пользователи</a>
                    </li>    
                    <li class="nav-item">
                        <a class="nav-link" href="/statuses">Статусы</a>
                    </li>    
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Категории</a>
                    </li>  
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Панель администратора</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="/deleteImagesWithoutLink">Удалить неиспользуемые изображения</a>
                        </div>
                    </li>       
                @endif
            @endif

            @guest 
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li> 
                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li> 
                @endif
            @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
            </li> 

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a class="navbar-brand" href="/users/user/{{Auth::user()->id}}">{{ Auth::user()->name }}</a>
        @endguest          
        </ul>
    </div>
</nav>