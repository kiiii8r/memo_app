@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')

        <div class="card-header">メモ内容</div>
        <div class="card-body my-card-body">
            閲覧したいメモを選択してください。
        </div>

@endsection
