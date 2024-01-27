<?php
namespace App\Repositories;

use App\Models\gift;
use App\Models\price_unit;
use Illuminate\Support\Facades\DB;

class giftRepository
{
    public static function all($user_id)
    {
        return gift::where(['user_id' => $user_id, 'gift_status' => 1])->get()->sortByDesc('created_at');
    }

    public static function all_basedOnProduct($user_id, $my_product)
    {
        return gift::where(['user_id' => $user_id , 'my_product' => $my_product , 'gift_status' => 1])
            ->get()
            ->sortByDesc('created_at');
    }

    public static function list($user_id)
    {
        return gift::with('user')
        ->where('user_id', '!=', $user_id)
        ->where('gift_status', 1)
        ->select('*', 'gifts.id as gift_id', 'gifts.created_at as gifts_created_at')
        ->orderByDesc('gifts_created_at')
        ->paginate(20);
       
        /* return DB::table('gifts')
            ->join('users', 'users.id', '=', 'gifts.user_id')
            ->where('user_id', '!=', $user_id)
            ->where('gift_status', 1)
            ->select('*', 'gifts.id as gift_id', 'gifts.created_at as gifts_created_at')
            ->get()->sortByDesc('gifts_created_at')->values(); */
    }

    public static function gift_details($gift_id )
    {
        return DB::table('gifts')
        ->join('users', 'users.id', '=', 'gifts.user_id')
        ->where(['gifts.id' => $gift_id, 'gift_status' => 1])
        ->select('*', 'gifts.id as gift_id', 'gifts.created_at as gifts_created_at')
        ->get();
    }

    public static function followings_gift($id)
    {
        return DB::table('gifts')
        ->join('users', 'gifts.user_id', '=', 'users.id')
        ->join('userfollows', 'gifts.user_id', '=', 'userfollows.follow_id')
        ->where('userfollows.user_id', $id )
        ->where(['follow_status' => 1, 'gift_status' => 1])
        ->select('*', 'gifts.id as gift_id', 'gifts.user_id as uid', 'gifts.created_at as gifts_created_at')
        ->orderByDesc('gifts_created_at')
        ->paginate(10);
    }

    public static function increase($gift_id, $field)
    {
        gift::where('id', $gift_id)->increment($field);
    }

    public static function get($gift_id, $user_id)
    {
        return gift::where(['id' => $gift_id, 'user_id' => $user_id, 'gift_status' => 1])->first();
    }

    public static function destroy($gift_id, $user_id)
    {
        return gift::where(['id' => $gift_id, 'user_id' => $user_id, 'gift_status' => 1])
            ->update(['gift_status' => 0]);

    }

    public static function create($req)
    {
        return gift::create($req); 
    }

    public static function update($gift_id, $request)
    {
        gift::where('id', $gift_id)->update($request);
    }

    public static function search($request)
    {
        return gift::where('gift_name', 'like', '%'.$request.'%')->get();
    }

    public static function price_unit($id)
    {
        $unit = price_unit::where('id', $id)->first();
        if (!$unit) {
            return '?';
        }
        return $unit->symbol; 
    }

    public static function units()
    {
        return price_unit::all();
    }
    
}