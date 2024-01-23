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
    public static function create($req)
    {
        return userevent::create($req); 
    }

    public static function update($event_id, $request)
    {
        return userevent::where('id', $event_id)->update($request); 
    }

    public static function get($event_id, $user_id)
    {
        return userevent::where([
            'user_id' => $user_id,
            'id' => $event_id, 
            'status' => 1
        ])->first(); 
    }

    public static function destroy($event_id)
    {
        return userevent::where([
            'id' => $event_id,
            'status' => 1
        ])->update(['status' => 0]); 
    }

    public static function detail($user_id, $event_id)
    {
        return userevent::where([
            'user_id' => $user_id, 
            'id' => $event_id, 
            'status' => 1
        ])->first(); 
    }

    public static function list($user_id)
    {
        return userevent::where([
            'user_id' => $user_id, 
            'status' => 1
            ])
        ->get()
        ->sortByDesc('created_at')
        ->values();
    }

    public static function type($id)
    {
        try{
            return event::where('id', $id)->first()->name;
        }
        catch(\Exception $exception){
            return "none";
        }
    }

    public static function remaining_days($date)
    {
        $event_date = Carbon::create($date);
        $currentDate = Carbon::now();
        $eventThisYear = $event_date->copy()->year($currentDate->year);
        if ($currentDate > $eventThisYear) {
            $eventThisYear->addYear();
        }
        return  now()->diffInDays(Carbon::parse($eventThisYear));
    }

    public static function rel_type($relation)
    {
        try{
            return relationship::where('id', $relation)->first()->name;
        }
        catch(\Exception $exception){
            return "none";
        }
    }

    public static function events()
    {
        return event::all();
    }

    public static function relationships()
    {
        return relationship::all();
    }
}