<?php
namespace App\Repositories;

use App\Models\giftlike;
use App\Models\giftUser;

class giftRepository
{

    static function increase($gift_id , $field){
        giftUser::where('gift_id',$gift_id)->increment($field);
    }
}