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
        // メモを取得
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('create', compact('memos'));
    }

    // 新規メモ作成
    public function store(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();

        // トランザクション開始
        DB::transaction(function() use($posts) {
            // メモIDをインサートし取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);

            // 新規タグがすでにtagsテーブルに存在するのかチェック
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])->exists();

            if( !empty($posts['new_tag']) && !$tag_exists){
                // 新規タグが既に存在しなければ、tagsテーブルにインサート→IDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにインサートして、メモとタグを紐付ける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
        });
        // ↑トランザクション範囲↑

        // Memoテーブルにインサートする
        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);

        // ホーム画面に戻る
        return redirect( route('home') );
    }

    // メモ編集
    public function edit($id)
    {
        // メモを取得
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        // メモを一つだけ取得
        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }

    // メモ更新
    public function update(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();

        // 指定のMemoレコードをアップデートする
        Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);

        // ホーム画面に戻る
        return redirect( route('home') );
    }

    // メモ削除機能
    public function destroy(Request $request)
    {
        // $postがpostされた内容を全て取得
        $posts = $request->all();

        // 指定のMemoレコードをアップデートする
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        // ホーム画面に戻る
        return redirect( route('home') );
    }
}