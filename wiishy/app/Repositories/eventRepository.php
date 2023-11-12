<?php
namespace App\Repositories;

use App\Models\userevent;
use App\Models\event;
use Illuminate\Support\Facades\DB;

class eventRepository
{
    static function create($req){
        return userevent::create($req); 
    }

    static function list($user_id){
        return userevent::where(['user_id'=>$user_id,'status'=>1])->get()->sortByDesc('created_at')->values();
    }

    static function type($id){
        try{
            return event::where('id',$id)->first()->name;
        }
        catch(\Exception $exception){
            return "none";
        }
    }
}