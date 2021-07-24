<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function mypage()
    {
        // $postがpostされた内容を全て取得
        $user = User::where('id', '=', \Auth::id())
        ->get();

        return view('mypage', compact('user'));
    }

    // ユーザー更新
    public function user_update(Request $request)
    {
    // $postがpostされた内容を全て取得
    $posts = $request->all();

    $request->validate(['intro' => 'max:255|nullable', 'email' => 'required|email', 'twitter' => 'url|nullable', 'github' => 'url|nullable', 'qiita' => 'url|nullable']);

    // 指定のUserレコードをアップデートする
    User::where('id',  \Auth::id())->update(['intro' => $posts['intro'], 'email' => $posts['email'], 'twitter' => $posts['twitter'], 'github' => $posts['github'], 'qiita' => $posts['qiita']]);

    return redirect('/mypage/');
    }
}