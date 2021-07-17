@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')
<div class="card">

    <div class="card-header d-flex justify-content-between">メモ編集
        <form id="delete-form" action="{{ route('destroy') }}" method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <button class="delete" type="submit" id="delete"><i class="far fa-trash-alt"></i></button>
        </form>
    </div>

    <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
        <div class="mb-3 d-flex justify-content-center">
            <iframe width="70%" height="250" src="https://www.youtube.com/embed/{{$youtube}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
           </div>
        @csrf
        <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
        <div class="mb-3">
            <textarea class="form-control" name="content" rows="3" maxlength="255" placeholder="ここにメモを入力">{{ $edit_memo[0]['content'] }}</textarea>
        </div>
        @error('content')
            <div class="alert alert-danger">メモ内容を入力してください</div>
        @enderror
        <div class="mb-3">
            <input type="text" class="form-control" name="url" placeholder="YoutubeのURLを入力" value="{{ $edit_memo[0]['url'] }}">
        </div>
        @error('url')
            <div class="alert alert-danger">YoutubeのURLを入力してください</div>
        @enderror
        @foreach($tags as $tag)
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
        </div>
        @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" maxlength="18" placeholder="新しいタグを入力"/>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>

@endsection
