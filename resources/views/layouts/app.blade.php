<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('javascript')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="/css/layout.css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
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
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('mypage') }}">
                                        マイページ
                                    </a>
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
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="fix-width">
            <div class="row">
                <div class="col-sm-12 col-md-2 p-0">
                    <div class="card">
                        <div class="card-header">ジャンル選択</div>
                        <div class="pl-10 card-body card-body-genre">
                            {{-- 各ユーザーのタグ一覧表示 --}}
                            @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                                <form class="card-body" action="{{ route('index') }}" method="GET">
                            @else
                                <form class="card-body" action="{{ route('search') }}" method="GET">
                            @endif
                                @csrf
                                <select name="genre">
                                    @foreach(config('genre') as $key => $genre)
                                    <option value="{{ $key }}"  {{ (INT) \Request::query('genre') === $key ? 'selected' : '' }} ) >{{ $genre }}</option>
                                    @endforeach
                                </select>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="mt-4 btn btn-primary">検索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">タグ一覧
                            @if(Request::is('search/*') || Request::is('read/*'))
                            <div class="small d-inline">(上位30件)</div>
                            @endif
                        </div>
                        <div class="pl-10 card-body card-body-tags">
                            {{-- 各ユーザーのタグ一覧表示 --}}
                            @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                                <a href="/" class="mb-2 card-text d-block">すべて表示</a>
                            @else
                                <a href="/search" class="mb-2 card-text d-block">すべて表示</a>
                            @endif

                            @foreach($tags as $tag)
                                @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                                <a href="/?tag={{ $tag['id'] }}" class="card-text d-block mb-2">
                                    {{ $tag['name'] }}
                                    ({{ $tag['count'] }})

                                    {{-- タグに紐付くメモ数が0の場合タグを削除できる --}}
                                    @if($tag['count'] === 0)
                                    <form class="d-inline" id="delete-form" action="{{ route('tag_destroy') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tag_id" value="{{ $tag['id'] }}" />
                                        <button class="ml-2 text- btn btn-outline-danger btn-sm" type="submit" id="delete">削除</button>
                                    </form>
                                    @endif
                                </a>
                                @else
                                    <a href="/search/?tag={{ $tag['id'] }}" class="card-text d-block mb-2">{{ $tag['name'] }} ({{ $tag['count'] }})</a>
                                @endif

                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 p-0">
                    <div class="card">
                        @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                            <div class="card-header">メモ一覧
                                <a href="{{ route('index') }}" class="text-secondary"><i class="fas fa-plus"></i></a>
                            </div>
                            <div class="card-body my-card-body">
                                <div class="mb-3 text-right border-bottom">
                                    <a href="{{ route('search') }}" class="text-secondary">他ユーザーのメモ一覧へ</a>
                                </div>
                                {{-- 各ユーザーのメモ一覧表示 --}}
                                @foreach($memos as $memo)
                                    <a href="/content/{{ $memo['id'] }}" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>
                                @endforeach
                            </div>
                        @else
                            @if(Request::is('user/*'))
                            <div class="card-header">{{ $user[0]['name'] }}のメモ一覧
                            </div>
                            @else
                            <div class="card-header">他ユーザのメモ一覧
                            </div>
                            @endif
                            <div class="card-body my-card-body">
                                <div class="mb-3 text-right border-bottom">
                                <a href="{{ route('index') }}" class="text-secondary">自分のメモ一覧へ</a>
                                @if(Request::is('user/*'))
                                <div class="d-inline ml-2 text-secondary">/</div>
                                <a href="{{ route('search') }}" class="text-secondary ml-2">他ユーザーのメモ一覧へ</a>
                                @endif
                                </div>
                                {{-- 全ユーザーのメモ一覧表示 --}}
                                @foreach($other_memos as $memo)
                                    <a href="/read/{{ $memo['id'] }}" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 p-0">
                    <div class="card">

                @yield('content')

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
