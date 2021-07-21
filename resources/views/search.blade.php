@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')
<div class="col-sm-12 col-md-4 p-0">
    <div class="card">
        <div class="card-header">他ユーザのメモ一覧
        </div>
        <div class="card-body my-card-body">
        <div class="mb-3 text-right border-bottom">
         <a href="{{ route('index') }}" class="text-secondary">自分のメモ一覧へ</a>
            {{-- 全ユーザーのメモ一覧表示 --}}
            @foreach($other_memos as $memo)
                <a href="" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>
            @endforeach
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-6 p-0">
    <div class="card">

    </div>
</div>

@endsection