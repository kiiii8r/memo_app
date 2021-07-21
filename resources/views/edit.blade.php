@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')
<div class="col-sm-12 col-md-4 p-0">
    <div class="card">
        <div class="card-header">メモ一覧
            <a href="{{ route('index') }}" class="text-secondary"><i class="fas fa-plus"></i></a>
        </div>

        <div class="card-body my-card-body">
            <div class="mb-3 text-right border-bottom">
                <a href="{{ route('search') }}" class="text-secondary">他ユーザーのメモ一覧へ</a>
            </div>
            {{-- 各ユーザーのメモ一覧表示 --}}
            @foreach($memos as $memo)
                <a href="/edit/{{ $memo['id'] }}" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>
            @endforeach
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-6 p-0">
    <div class="card">

        <div class="card-header d-flex justify-content-between">メモ編集
            <form id="delete-form" action="{{ route('destroy') }}" method="POST">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
                <button class="delete" type="submit" id="delete"><i class="far fa-trash-alt"></i></button>
            </form>
        </div>

        <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
            <div class="mb-2 d-flex justify-content-center">
                <iframe width="70%" height="250" src="https://www.youtube.com/embed/{{$youtube}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="text-right ml-3">
                    <div class="mb-3">
                        <h5>評価</h5>
                        <select name="score">
                            @foreach(config('score') as $key => $score)
                                <option value="{{ $key }}"  {{ $edit_memo[0]['score'] === $key ? 'selected' : ''}}>{{ $score }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <h5>ジャンル</h5>
                        <select name="genre">
                            @foreach(config('genre') as $key => $genre)
                                <option value="{{ $key }}"  {{ $edit_memo[0]['genre'] === $key ? 'selected' : ''}}>{{ $genre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <a href="{{ $edit_memo[0]['url'] }}" class="text-danger btn-lg"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <div class="mb-2">
                <textarea class="form-control" name="content" rows="3" maxlength="255" placeholder="ここにメモを入力">{{ $edit_memo[0]['content'] }}</textarea>
            </div>
            @error('content')
                <div class="alert alert-danger">メモ内容を入力してください</div>
            @enderror
            <div class="mb-2">
                <input type="text" class="form-control" name="url" placeholder="YoutubeのURLを入力" value="{{ $edit_memo[0]['url'] }}">
            </div>
            @error('url')
                <div class="alert alert-danger">YoutubeのURLを入力してください</div>
            @enderror
            <div class="d-flex justify-content-start">

            </div>
            <div class="scroll-overflow-edit">
                @foreach($tags as $tag)
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
                </div>
                @endforeach
            </div>
            <input type="text" class="form-control w-50 mb-3" name="new_tag" maxlength="18" placeholder="新しいタグを入力"/>
            <button type="submit" class="btn btn-primary">更新</button>
        </form>
    </div>
</div>

@endsection
