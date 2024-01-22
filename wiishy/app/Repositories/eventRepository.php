<?php
namespace App\Repositories;

use App\Models\userevent;
use App\Models\event;
use App\Models\relationship;
use Carbon\Carbon;

class eventRepository
{
    /**
     * create
     * 
     * @param array $req
     * @return array
     */
    static function create($req)
    {
        return userevent::create($req); 
    }

    static function update($event_id, $request)
    {
        return userevent::where('id', $event_id)->update($request); 
    }

    static function get($event_id, $user_id)
    {
        return userevent::where(['user_id' => $user_id, 'id' => $event_id, 'status' => 1])->first(); 
    }

    static function destroy($event_id)
    {
        return userevent::where(['id' => $event_id, 'status' => 1])->update(['status' => 0]); 
    }

    static function detail($user_id, $event_id)
    {
        return userevent::where(['user_id' => $user_id, 'id' => $event_id, 'status' => 1])->first(); 
    }

    static function list($user_id)
    {
        return userevent::where(['user_id' => $user_id, 'status' => 1])->get()->sortByDesc('created_at')->values();
    }

    static function type($id)
    {
        try{
            return event::where('id', $id)->first()->name;
        }
        catch(\Exception $exception){
            return "none";
        }
    }

    static function remaining_days($date)
    {
        $event_date = Carbon::create($date);
        $currentDate = Carbon::now();
        $eventThisYear = $event_date->copy()->year($currentDate->year);
        if ($currentDate > $eventThisYear) {
            $eventThisYear->addYear();
        }
        return  now()->diffInDays(Carbon::parse($eventThisYear));
    }

    static function rel_type($relation)
    {
        try{
            return relationship::where('id', $relation)->first()->name;
        }
        catch(\Exception $exception){
            return "none";
        }
    }

    static function events()
    {
        return event::all();
    }

    static function relationships()
    {
        return relationship::all();
    }
}