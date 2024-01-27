<?php
namespace App\Repositories;

use App\Models\provider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class userRepository
{
    public static function all($user_id)
    {
        return User::where([
            'id' => $user_id,
            'status' => 1
        ])->first();
    }

    /* static function details($user_id){
        return User::where('id',$user_id)->select('name','family','user_image_url');
    } */

    public static function list()
    {
        return User::paginate(15);
    }

    public static function destroy($user_id)
    {
        return User::where([
            'id' => $user_id,
            'status' => 1
        ])->update(['status' => 0]);
    }

    public static function create($req)
    {
        return User::create($req);
    }

    public static function get($id)
    {
        return User::where([
            'id' => $id,
            'status' => 1
        ])->first();
    }

    public static function update($id, $request)
    {
        User::where('id', $id)->update($request);
    }

    public static function provider($provider)
    {
        return provider::where('name', $provider)->first();
    }

    public static function check($req, $provider_id)
    {
        return User::where([
            'email' => $req->email,
            'provider_id' => $provider_id,
            'status' => 1
        ])->first();
    }

    public static function token($user)
    {
        return $user->createToken('wiishy_token')->plainTextToken;
    }

    /* static function provider_id_return($id){
        return User::where('id',$id)->first()->provider_id;
    } */

    public static function age($user_birthday)
    {
        $birthday = date_create($user_birthday);
        return Carbon::parse($birthday)->age;
    }

    public static function search($request)
    {
        return User::where(DB::raw('concat(name, family)'), 'like', '%'.$request.'%')->get();
    }
}