<?php
namespace App\Repositories;

use App\Models\provider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class userRepository
{
    static function all($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->first();
    }

/*     static function details($user_id){
        return User::where('id',$user_id)->select('name','family','user_image_url');
    } */

    static function list(){
        return User::all();
    }

    static function destroy($user_id){
        return User::where(['id'=>$user_id,'status'=>1])->update(['status'=>0]);
    }

    static function create($req){
        return User::create($req);
    }

    static function get($id){
        return User::where(['id'=>$id,'status'=>1])->first();
    }

    static function update($id,$request){
        User::where('id',$id)->update($request);
    }

    static function provider($provider){
        return provider::where('name',$provider)->first();
    }

    static function check($req , $provider_id){
        return User::where(['email'=>$req->email ,'provider_id'=>$provider_id ,'status'=>1])->first();
    }

    static function token($user){
        return $user->createToken('wiishy_token')->plainTextToken;
    }

    /* static function provider_id_return($id){
        return User::where('id',$id)->first()->provider_id;
    } */

    static function age($user_birthday){
        $birthday=date_create($user_birthday);
        return Carbon::parse($birthday)->age;
    }

    static function search($request){
        return User::where(DB::raw('concat(name, family)'), 'like', '%'.$request.'%')->get();
    }
}