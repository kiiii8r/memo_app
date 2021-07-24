<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    public function getMyMemo(){
        $query_tag = \Request::query('tag');
        $query_genre = \Request::query('genre');
        $query = Memo::query()->select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC');
        // もしクエリパラメータtagがあれば
        if(!empty($query_tag)) {
            // タグで絞り込み
            $memos = $query
                ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                ->where('memo_tags.tag_id', '=', $query_tag)
                ->get();
        }else if(!empty($query_genre)){
            // ジャンルで絞り込み
            $memos = $query
            ->where('memos.genre', '=', $query_genre)
            ->get();
        }else{
            // タグがなければ全て取得
            $memos = $query
                ->get();
        }

        return $memos;
    }
}