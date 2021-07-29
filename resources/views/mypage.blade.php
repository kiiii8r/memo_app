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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="/js/confirm.js"></script>

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
                                    <a class="dropdown-item" href="{{ route('index') }}">
                                        メモ一覧へ
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
                <div class="col-sm-12 col-md-4 p-0">
                    <div class="card">
                        <div class="card-header">マイページ</div>
                        <div class="card-body my-card-body">
                            <div class="mb-2 d-flex justify-content-center">

                                <form action="{{ route('image_up') }}" method="POST" enctype="multipart/form-data">
                                      @csrf

                                    <div class="text-center image-space">
                                        @if(isset($user[0]['image']))
                                            <img class="rounded" src="{{ asset('storage/' . $user[0]['image']) }}" alt="画像なし" width="300" height="300">
                                        @else
                                            <div class="h-100 d-flex align-items-center justify-content-center">画像なし</div>
                                        @endif
                                    </div>

                                    <h2 class="m-3 text-center">{{$user[0]['name']}}のプロフィール</h2>

                                    @error('file')
                                    <div class="alert alert-danger">画像を選択してください。</div>
                                    @enderror

                                    <label for="photo"></label>
                                    <input type="file" class="m-3 p-2 border" name="file">

                                    <div class="m-2 d-flex justify-content-center">
                                    <button id="image-confirm" type="submit" class="btn btn-primary">プロフィール画像更新</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-8 p-0">
                    <div class="card">
                        <div class="card-header">情報</div>
                        <div class="card-body my-card-body">
                            <div class="mb-2 ml-4 d-flex justify-content-start">
                                <form class="w-75 d-left" action="{{ route('user_update') }}" method="POST">
                                        @csrf
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">自己紹介</label>
                                        <textarea class="form-control" name="info" rows="3" maxlength="500" placeholder="自己紹介を入力">{{ $user[0]['info'] }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">メールアドレス</label>
                                        <input type="text" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{ $user[0]['email'] }}">
                                    </div>
                                    @error('email')
                                    <div class="alert alert-danger">メールアドレスが間違っている、もしくは既に登録されています。</div>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Twitter URL</label>
                                        <input type="text" name="twitter" class="form-control" id="exampleFormControlInput1" placeholder="https://twitter.com/.." value="{{ $user[0]['twitter'] }}">
                                    </div>
                                    @error('twitter')
                                    <div class="alert alert-danger">TwitterのURLを入力してください。</div>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">GitHub URL</label>
                                        <input type="text" name="github" class="form-control" id="exampleFormControlInput1" placeholder="https://github.com/.." value="{{ $user[0]['github'] }}">
                                    </div>
                                    @error('github')
                                    <div class="alert alert-danger">githubのURLを入力してください。</div>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Qiita URL</label>
                                        <input type="text" name="qiita" class="form-control" id="exampleFormControlInput1" placeholder="https://qiita.com/.." value="{{ $user[0]['qiita'] }}">
                                    </div>
                                    @error('qiita')
                                    <div class="alert alert-danger">qiitaのURLを入力してください。</div>
                                    @enderror
                                    <div class="d-flex justify-content-end">
                                        <button id="profile-confirm" type="submit" class="btn btn-primary">更新</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
