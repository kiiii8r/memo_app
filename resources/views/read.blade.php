@extends('layouts.app')

@section('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
crossorigin="anonymous"></script>
<script src="/js/confirm.js"></script>

@endsection

@section('content')

<div class="card-header"><a href="/user/{{ $other_memo[0]['user_id'] }}">{{ $other_memo[0]['user_name']}}</a>さんのメモ内容</div>
    <div class="card-body my-card-body">
    <div class="mb-2 d-flex justify-content-center">
        <iframe width="70%" height="250" src="https://www.youtube.com/embed/{{$youtube}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <div class="text-right ml-3">
            <div class="mb-3">
                <h5>評価</h5>
                <div>
                    @foreach(config('score') as $key => $score)
                        @if($key === $other_memo[0]['score'])
                            {{ $score }}
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mb-2">
                <h5>ジャンル</h5>
                <div>
                    @foreach(config('genre') as $key => $genre)
                        @if($key === $other_memo[0]['genre'])
                            {{ $genre }}
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mb-2">
                <a href="{{ $other_memo[0]['url'] }}" class="text-danger btn-lg"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <div class="m-3 scroll h-220px border-top border-bottom">
        {!!nl2br(e($other_memo[0]['content']))!!}
    </div>

    <div class="scroll h-60px">登録タグ:
        @foreach($tags as $tag)
            @if(in_array($tag['id'], $include_tags))
                <a class="ml-2" href="/search/?tag={{ $tag['id'] }}">
                    {{ $tag['name'] }} ({{ $tag['count'] }})
                </a>
            @endif
        @endforeach
    </div>
</div>


@endsection
