<?php
namespace App\Repositories;

use App\Models\gift;
use App\Models\giftlike;
use Illuminate\Support\Facades\DB;

class likeRepository
{
    public static function check($gift_id , $user_id)
    {
        $like = giftlike::where([
            'gift_id' => $gift_id,
            'user_id' => $user_id
        ])->first();

        if ($like) {
            return true;
        } 
        return false;
    }

    public static function like($gift_id, $user_id)
    {
        giftlike::create([
            'user_id' => $user_id,
            'gift_id' => $gift_id
        ]);
    }

    public static function dislike($gift_id, $user_id)
    {
        giftlike::where([
            'gift_id' => $gift_id,
            'user_id' => $user_id
        ])->delete();
    }

    public static function increase($gift_id)
    {
        gift::where('id', $gift_id)->increment('gift_like');
    }

    public static function decrease($gift_id)
    {
        gift::where('id', $gift_id)->decrement('gift_like');
    }

    public static function count($gift_id)
    {
        return gift::where([
            'gift_id' => $gift_id,
            'gift_status' => 1
        ])->first()->gift_like;
    }

    public static function list($gift_id)
    {
        return DB::table('giftlike')
        ->join('users', 'giftlike.user_id', '=', 'users.id')
        ->where('giftlike.gift_id', $gift_id)
        ->select('user_id', 'name', 'family', 'user_image_url', 'giftlike.created_at as like_date')
        ->get()->sortByDesc('like_date')->values();
    }
}