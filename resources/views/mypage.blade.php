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
                                <img src="{{ asset('storage/profiles/'.$user->profile_image) }}" alt="プロフィール画像">
                                <form method="post" action="{{ route('user.update', ['user' => $user->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    <label for="profile_image">プロフィール画像</label>

                                    <label for="profile_image" class="btn">
                                      <img src="{{ asset('storage/profiles/'.$user->profile_image) }}" id="img">
                                      <input id="profile_image" type="file"  name="profile_image" onchange="previewImage(this);">
                                    </label>

                                    <button type="submit" class="btn btn-primary">
                                      変更
                                    </button>
                                  </form>

                                  <script>
                                    function previewImage(obj)
                                    {
                                      var fileReader = new FileReader();
                                      fileReader.onload = (function() {
                                        document.getElementById('img').src = fileReader.result;
                                      });
                                      fileReader.readAsDataURL(obj.files[0]);
                                    }
                                  </script>

                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-8 p-0">
                    <div class="card">
                        <div class="card-header">情報</div>
                        <div class="card-body my-card-body">
                            <div class="mb-2 ml-4 d-flex justify-content-start">
                                <form id="delete-form" class="w-75 d-left" action="{{ route('user_update') }}" method="POST">
                                        @csrf
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">自己紹介</label>
                                        <textarea class="form-control" name="intro" rows="3" maxlength="255" placeholder="自己紹介を入力">{{ $user[0]['intro'] }}</textarea>
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
                                        <input type="text" name="twitter" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{ $user[0]['twitter'] }}">
                                    </div>
                                    @error('twitter')
                                    <div class="alert alert-danger">TwitterのURLを入力してください。</div>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">GitHub URL</label>
                                        <input type="text" name="github" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{ $user[0]['github'] }}">
                                    </div>
                                    @error('github')
                                    <div class="alert alert-danger">githubのURLを入力してください。</div>
                                    @enderror
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Qiita URL</label>
                                        <input type="text" name="qiita" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{ $user[0]['qiita'] }}">
                                    </div>
                                    @error('qiita')
                                    <div class="alert alert-danger">qiitaのURLを入力してください。</div>
                                    @enderror
                                    <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">更新</button>
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
