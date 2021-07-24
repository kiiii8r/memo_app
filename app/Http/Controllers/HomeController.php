<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // メモ一覧取得
    public function index()
    {

        $tags = Tag::select('tags.id', 'tags.name', Tag::raw('count(memo_tags.tag_id) count'))
            ->leftJoin('memo_tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy(MemoTag::raw('count(memo_tags.tag_id)'), 'DESC')
            ->groupBy('tags.id', 'tags.name', 'memo_tags.tag_id')
            ->get();

        return view('create', compact('tags'));
    }

    // 新規メモ作成
    public function store(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();
        $request->validate(['content' => 'required|max:255', 'url' => 'required']);

        // トランザクション開始
        DB::transaction(function() use($posts) {
            // メモIDをインサートし取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'url' => $posts['url'], 'genre' => $posts['genre'], 'score' => $posts['score'],  'user_id' => \Auth::id()]);

            // 新規タグがすでにtagsテーブルに存在するのかチェック
            $tag_exists = Tag::where('user_id', '=', \Auth::id())
                ->where('name', '=', $posts['new_tag'])
                ->exists();

            if( !empty($posts['new_tag']) && !$tag_exists){
                // 新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
            // 既存タグが紐づけられた場合
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });
        // ↑トランザクション範囲↑

        // ホーム画面に戻る
        return redirect( route('index') );
    }

    // メモ編集
    public function edit($id)
    {
        // メモを一つだけ取得
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
            ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('memos.user_id', '=', \Auth::id())
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();

        // url内容を取得
        $data = $edit_memo[0]['url'];
        $youtube = $edit_memo[0]['url'];

        // フレーム確認
        if (strpos($youtube, "iframe") != true)
        {
            // URL確認
            if (strpos($youtube, "watch") != false)
            {
                // コード変換
                $youtube = substr($youtube, (strpos($youtube, "=")+1));
            }
            else
            {
                // 短縮URL変換
                $youtube = substr($youtube, (strpos($youtube, "youtu.be/")+9));
            }
        }

        // 編集するメモとタグを紐付け
        $include_tags = [];
        foreach($edit_memo as $memo){
            array_push($include_tags, $memo['tag_id']);
        }

        // タグ一覧を取得
        $tags = Tag::select('tags.id', 'tags.name', Tag::raw('count(memo_tags.tag_id) count'))
        ->leftJoin('memo_tags', 'memo_tags.tag_id', '=', 'tags.id')
        ->where('user_id', '=', \Auth::id())
        ->whereNull('deleted_at')
        ->orderBy(MemoTag::raw('count(memo_tags.tag_id)'), 'DESC')
        ->groupBy('tags.id', 'tags.name', 'memo_tags.tag_id')
        ->get();

        return view('edit', compact('edit_memo', 'include_tags' ,'tags', 'youtube'));
    }

    // メモ更新
    public function update(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();
        $request->validate(['content' => 'required|max:255', 'url' => 'required|url']);

        // トランザクションスタート
        DB::transaction(function () use($posts){
            // 指定のMemoレコードをアップデートする
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content'], 'url' => $posts['url'], 'genre' => $posts['genre'], 'score' => $posts['score']]);
            // 一旦メモとタグの紐付けを削除
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
            // 再度メモとタグの紐付け
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
                }
            }
            // 新しいタグの入力があれば代入
            $tag_exists = Tag::where('user_id', '=', \Auth::id())
                ->where('name', '=', $posts['new_tag'])
                ->whereNull('tags.deleted_at')
                ->exists();

            // 新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
            if( !empty($posts['new_tag']) && !$tag_exists){

                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }
        });
        // トランザクション終了


        // ホーム画面に戻る
        return redirect('/edit/'. $posts['memo_id']);
    }

    // メモ削除機能
    public function destroy(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();

        // 指定のMemoレコードをアップデートする
        Memo::where('id', $posts['memo_id'])
            ->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        // ホーム画面に戻る
        return redirect( route('index') );
    }

    public function tag_destroy(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();

        // 指定のtagを削除する
        Tag::where('id', $posts['tag_id'])
            ->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        // ホーム画面に戻る
        return redirect( route('index') );
    }


    public function search()
    {
        $tags = MemoTag::select('tags.id', 'tags.name', Tag::raw('count(memo_tags.tag_id) as count'))
            ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->where('user_id', '!=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy(Tag::raw('count(memo_tags.tag_id)'), 'DESC')
            ->groupBy('memo_tags.tag_id')
            ->get();

        $query_tag = \Request::query('tag');
        $query_genre =  \Request::query('genre');
        $query = Memo::query()
            ->select('memos.*')
            ->where('user_id', '!=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC');

        // もしクエリパラメータtagがあれば
        if(!empty($query_tag)) {
            // タグで絞り込み
            $other_memos = $query
                ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->where('memo_tags.tag_id', '=', $query_tag)
                ->get();
        }else if(!empty($query_genre)){
            $other_memos = $query
            ->where('memos.genre', '=', $query_genre)
            ->get();
        }else{
            // タグがなければ全て取得
            $other_memos = $query
                ->get();
        }

        return view('search', compact('other_memos', 'tags'));
    }


    // 他ユーザのメモ一一覧機能
    public function read($id)
    {

        $other_memos = Memo::select('memos.*')
            ->where('user_id', '!=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        // メモを一つだけ取得
        $other_memo = Memo::select('memos.*', 'tags.id AS tag_id', 'users.name AS user_name')
            ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
            ->leftJoin('users', 'memos.user_id', '=', 'users.id')
            ->where('memos.id', '=', $id)
            ->whereNull('memos.deleted_at')
            ->get();

        // 編集するメモとタグを紐付け
        $include_tags = [];
        foreach($other_memo as $memo){
            array_push($include_tags, $memo['tag_id']);
        }

        $tags = MemoTag::select('tags.id', 'tags.name', Tag::raw('count(memo_tags.tag_id) as count'))
        ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
        ->where('user_id', '!=', \Auth::id())
        ->whereNull('deleted_at')
        ->orderBy(Tag::raw('count(memo_tags.tag_id)'), 'DESC')
        ->groupBy('memo_tags.tag_id')
        ->limit(30)
        ->get();

        // url内容を取得
        $data = $other_memos[0]['url'];
        $youtube = $other_memo[0]['url'];

        // // フレーム確認
        if (strpos($youtube, "iframe") != true)
        {
            // URL確認
            if (strpos($youtube, "watch") != false)
            {
                // コード変換
                $youtube = substr($youtube, (strpos($youtube, "=")+1));
            }
            else
            {
                // 短縮URL変換
                $youtube = substr($youtube, (strpos($youtube, "youtu.be/")+9));
            }
        }

        return view('read', compact('other_memos', 'tags', 'youtube', 'other_memo', 'include_tags'));
    }
}