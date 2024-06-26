<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm border-bottom border-2 border-info">
    <div class="container">
        <a class="navbar-brand top-logo" href="{{ url('/') }}">
            {{ config('app.name', 'TechBookLibrary') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.reviews.index') }}">{{ __('レビューリスト') }}</a>
                    </li>
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.books.search') }}">
                            <i class="fa fa-search me-1"></i>{{ __('技術書検索') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.reviews.index') }}">
                            <i class="far fa-comments me-1"></i>{{ __('レビューリスト') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.favorite_books.index') }}">
                            <i class="fa fa-book-reader me-1"></i>{{ __('読みたい') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.read_books.index') }}">
                            <i class="fas fa-book me-1"></i>{{ __('読んだ') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.library.create') }}">
                            <i class="fa fa-university me-1"></i>{{ __('図書館登録') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('user.notification_books.index') }}">
                                {{ __('通知設定済書籍一覧') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('user.emails.edit') }}">
                                {{ __('メールアドレス変更') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('user.passwords.edit') }}">
                                {{ __('パスワード変更') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
