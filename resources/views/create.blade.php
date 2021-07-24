@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')

<div class="card-header">新規メモ作成</div>
<form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <textarea class="form-control" name="content" rows="8" maxlength="255" placeholder="ここにメモを入力"></textarea>
    </div>
    @error('content')
        <div class="alert alert-danger">メモ内容を入力してください</div>
    @enderror
    <div class="mb-3">
        <input type="text" class="form-control" name="url" placeholder="YoutubeのURLを入力">
    </div>
    @error('url')
    <div class="alert alert-danger">YoutubeのURLを入力してください</div>
    @enderror
    <div class="d-flex justify-content-start">
        <div class="mb-3">
            評価
            <select name="score">
                @foreach(config('score') as $key => $score)
                <option value="{{ $key }}">{{ $score }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 ml-3">
            ジャンル選択
            <select name="genre">
                @foreach(config('genre') as $key => $genre)
                <option value="{{ $key }}">{{ $genre }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="scroll-overflow-create">
        @foreach($tags as $tag)
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}">
            <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
        </div>
        @endforeach
    </div>
    <input type="text" class="form-control w-50 mb-3" name="new_tag" maxlength="18" placeholder="新しいタグを入力"/>
    <button type="submit" class="btn btn-primary">保存</button>
</form>

@endsection
