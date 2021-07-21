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
            </div>
            {{-- 全ユーザーのメモ一覧表示 --}}
            @foreach($other_memos as $memo)
                <a href="/read/{{ $memo['id'] }}" class="card-text d-block ellipsis mb-2">{{ $memo['content'] }}</a>
            @endforeach
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-6 p-0">
    <div class="card">

        <div class="card-header d-flex justify-content-between">メモ内容</div>
            <div class="mb-2 d-flex justify-content-center">
                {{-- <iframe width="70%" height="250" src="https://www.youtube.com/embed/{{$youtube}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                <div class="text-right ml-3">
                    <div class="mb-3">
                        <h5>評価</h5>
                        <select name="score">
                            @foreach(config('score') as $key => $score)
                                {{-- <option value="{{ $key }}"  {{ $other_memo[0]['score'] === $key ? 'selected' : ''}}>{{ $score }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <h5>ジャンル</h5>
                        <select name="genre">
                            @foreach(config('genre') as $key => $genre)
                                {{-- <option value="{{ $key }}"  {{ $other_memo[0]['genre'] === $key ? 'selected' : ''}}>{{ $genre }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        {{-- <a href="{{ $other_memo[0]['url'] }}" class="text-danger btn-lg"><i class="fab fa-youtube"></i></a> --}}
                    </div>
                </div>
            </div>
            @csrf
            {{-- <input type="hidden" name="memo_id" value="{{ $other_memo[0]['id'] }}" /> --}}
            <div class="mb-2">
                {{-- {{ $edit_memo[0]['content'] }} --}}
            </div>

            <div class="mb-2">
                {{-- {{ $other_memo[0]['url'] }} --}}
            </div>

            <div class="d-flex justify-content-start">

            </div>
            <div class="scroll-overflow-edit">
                @foreach($tags as $tag)
                <div class="form-check form-check-inline mb-3">
                    {{-- <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}> --}}
                    <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
                </div>
                @endforeach
            </div>
    </div>
</div>

@endsection
