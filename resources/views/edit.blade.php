@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')

<div class="card-header d-flex justify-content-between"><span>メモ編集
 <a href="/content/{{ $edit_memo[0]['id'] }}" class="text-secondary ml-1"><i class="fas fa-undo-alt"></i></a></span>
    <form id="delete-form" action="{{ route('destroy') }}" method="POST">
        @csrf
        <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
        <button class="delete" type="submit" id="delete"><i class="far fa-trash-alt"></i></button>
    </form>
</div>

<form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
    @csrf
    <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
    <div class="mb-2">
        <textarea class="form-control" name="content" rows="9" maxlength="500" placeholder="ここにメモを入力">{{ $edit_memo[0]['content'] }}</textarea>
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
    <div class="d-flex">
        <div class="mb-2">
        <div class="d-inline">評価：</div>
        <select name="score">
            @foreach(config('score') as $key => $score)
                <option value="{{ $key }}"  {{ $edit_memo[0]['score'] === $key ? 'selected' : ''}}>{{ $score }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2 ml-2">
            <div class="d-inline">ジャンル：</div>
        <select name="genre">
            @foreach(config('genre') as $key => $genre)
                <option value="{{ $key }}"  {{ $edit_memo[0]['genre'] === $key ? 'selected' : '' }}>{{ $genre }}</option>
            @endforeach
        </select>
    </div>

    </div>
    <div class="border-bottom d-flex">
        @php($rank = 0)
        <div class="w-50">人気のタグ</div>
        <div class="scroll h-80px">
            @foreach($all_tags as $tag)
                <div class="form-check form-check-inline mb-2">
                    @php($rank++)
                    <label class="form-check-label btn btn-outline-success btn-sm" for="{{ $tag['id'] }}">
                        @if(!(in_array($tag['id'], $include_tags)))
                        <input class="form-check-input small" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}">
                    @endif
                        {{ $rank }}位：{{ $tag['name'] }}({{ $tag['count'] }})
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex mt-1">
        <div class="w-25">マイタグ</div>
        <div class="scroll h-80px">
            @foreach($tags as $tag)
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" {{ in_array($tag['id'], $my_tags) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <input type="text" class="form-control w-50 mb-2" name="new_tag" maxlength="18" placeholder="新しいタグを入力"/>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">更新</button>
    </div>
</form>

@endsection
