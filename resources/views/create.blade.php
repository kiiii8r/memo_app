@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">新規メモ作成</div>
    <form class="card-body" action="{{ route('store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <textarea class="form-control" name="content" rows="3" maxlength="255" placeholder="ここにメモを入力"></textarea>
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
        @foreach($tags as $tag)
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}">
            <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
        </div>
        @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" maxlength="18" placeholder="新しいタグを入力"/>
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>

@endsection
