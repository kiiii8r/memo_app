@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')

        <div class="card-header">プロフィール</div>
        <div class="card-body my-card-body">
            <div class="text-center image-space">
                @if(isset($user[0]['image']))
                    <img class="rounded" src="{{ asset('storage/' . $user[0]['image']) }}" alt="画像なし" width="300" height="300">
                @else
                    <div class="h-100 d-flex align-items-center justify-content-center">画像なし</div>
                @endif
            </div>

            <h2 class="m-2 text-center">{{ $user[0]['name'] }}さん</h2>

            <div class="scroll h-160px border-top border-bottom">
                {!!nl2br(e($user[0]['info']))!!}
            </div>

            <div class="d-flex justify-content-center">
                @if(!empty($user[0]['twitter']))
                <a href="{{ $user[0]['twitter'] }}" class="text-danger btn-lg"><i class="fab fa-twitter text-primary icon"></i></a>
                @endif

                @if(!empty($user[0]['github']))
                <a href="{{ $user[0]['github'] }}" class="btn-lg"><i class="fab fa-github text-muted icon"></i></a>
                @endif

                @if(!empty($user[0]['github']))
                <a href="{{ $user[0]['qiita'] }}" class="btn-lg fa-stack ml-2">
                    <i class="fa fa-square fa-stack-2x text-success"></i>
                    <i class="fa fa-search fa-stack-1x fa-inverse fa-2x"></i>
                </a>
                   @endif
            </div>
        </div>

@endsection
