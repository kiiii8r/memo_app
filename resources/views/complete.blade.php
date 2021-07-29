@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="card">
                <div class="card-header">完了画面</div>

                <div class="card-body">
                    <h3 class="p-5 100px d-flex justify-content-center">パスワード変更完了</h3>
                    <a href="{{ route('index') }}" class="d-flex justify-content-center">メモ一覧へ</a>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
