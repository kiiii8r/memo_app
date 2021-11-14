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
                        <div class="card-header">絞り込み検索</div>
                        <div class="pl-10 card-body p-0 ml-3 search-box scroll">
                            @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                                <form class="card-body" action="{{ route('index') }}" method="GET">
                            @else
                                <form class="card-body" action="{{ route('search') }}" method="GET">
                                <input type="text" class="mt-2 mb-2" name="user_name" placeholder="ユーザー名検索" value=""/>
                            @endif
                                @csrf
                                <div class="mt-2">ジャンル選択</div>
                                <select name="genre">

                                    @foreach(config('genre') as $key => $genre)
                                        <option value="{{ $key }}"  {{ (INT) \Request::query('genre') === $key ? 'selected' : '' }} ) >{{ $genre }}</option>
                                    @endforeach
                                </select>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="mt-3 btn btn-primary">検索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">タグ一覧
                            @if(Request::is('search/*') || Request::is('search') || Request::is('read/*') || Request::is('user/*'))
                                <div class="small d-inline">(上位30件)</div>
                            @endif

                            @if(\Request::query('tag'))
                                @if(Request::is('search'))
                                    <a href="/search" class="mb-2 ml-1 card-text d-inline text-secondary"><i class="fas fa-undo-alt"></i></a>
                                @else
                                    <a href="/" class="mb-2 ml-1 card-text d-inline text-secondary"><i class="fas fa-undo-alt"></i></a>
                                @endif
                            @endif

                        </div>
                        <div class="pl-10 card-body scroll tags-box">

                            @foreach($tags as $tag)
                                @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))
                                <a href="/?tag={{ $tag['id'] }}" class="card-text btn-sm btn btn-outline-secondary mb-2">
                                    {{ $tag['name'] }}
                                    ({{ $tag['count'] }})
                                </a>
                                @else
                                    <a href="/search/?tag={{ $tag['id'] }}" class="card-text btn-sm btn btn-outline-secondary mb-2">{{ $tag['name'] }} ({{ $tag['count'] }})</a>
                                @endif
                            @endforeach


                            @if(Request::is('edit/*') || Request::is('/*') || Request::is('content/*'))

                                @if(!empty($no_tags))
                                    <div class="m-1 border-bottom">不要タグの削除</div>
                                @endif

                                <div class="card-text">
                                    @foreach($no_tags as $tag)
                                        <form class="d-inline" id="delete-form" action="{{ route('tag_destroy') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tag_id" value="{{ $tag->id }}" />
                                            <button class=" mb-2 btn-sm btn btn-outline-danger" type="submit" id="delete">
                                                {{ $tag->name }}
                                                ({{ $tag->count }})
                                            </button>
                                        </form>
                                    @endforeach
                                </div>

                            @endif

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
                                    <div class="d-flex">
                                        <a href="/read/{{ $memo['id'] }}" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>

                                        @if(Request::is('search/*') || Request::is('search') || Request::is('read/*'))
                                            ：<a href="/user/{{ $memo['user_id'] }}" class="ellipsis w-30px">{{ $memo['user_name'] }}</a>
                                        @endif
                                    </div>
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
