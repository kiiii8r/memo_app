<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MemoTag;
use App\Models\Tag;
use App\Models\Memo;

class UserController extends Controller
{
    public function mypage()
    {
        $user = User::where('id', '=', \Auth::id())
        ->get();
        return view('mypage', compact('user'));
    }

    public function user($id)
    {
       $user = User::where('id', '=', $id)
       ->get();

       $tags = MemoTag::select('tags.id', 'tags.name', Tag::raw('count(memo_tags.tag_id) as count'))
       ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
       ->where('user_id', '!=', \Auth::id())
       ->whereNull('deleted_at')
       ->orderBy(Tag::raw('count(memo_tags.tag_id)'), 'DESC')
       ->groupBy('memo_tags.tag_id')
       ->get();

       $other_memos = Memo::select('memos.*')
            ->where('user_id', '=', $id)
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();

        return view('user', compact('user', 'tags', 'other_memos'));
    }

    // ユーザー更新
    public function user_update(Request $request)
    {
    // $postがpostされた内容を全て取得
    $posts = $request->all();

    $request->validate(['info' => 'nullable', 'email' => 'required|email', 'twitter' => 'url|nullable', 'github' => 'url|nullable', 'qiita' => 'url|nullable']);

    // 指定のUserレコードをアップデートする
    User::where('id',  \Auth::id())->update(['info' => $posts['info'], 'email' => $posts['email'], 'twitter' => $posts['twitter'], 'github' => $posts['github'], 'qiita' => $posts['qiita']]);

    return redirect('/mypage/');
    }

    public function image_up(Request $request)
    {
        $this->validate($request, [
            'file' => [
                // 必須
                'required',
                // アップロードされたファイルであること
                'file',
                // 画像ファイルであること
                'image',
                // MIMEタイプを指定
                'mimes:jpeg,png',
            ]
        ]);

        if ($request->file('file')->isValid([])) {
            $image = $request->file->store('public');
            $image = str_replace('public/', '', $image);
            User::where('id',  \Auth::id())->update(['image' => $image]);

            return redirect('/mypage/');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors();
        }
    }

    public function complete()
    {

        return view('complete');
    }
}