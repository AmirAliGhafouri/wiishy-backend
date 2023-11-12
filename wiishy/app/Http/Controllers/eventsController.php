<?php

namespace App\Http\Controllers;

use App\Repositories\eventRepository;
use App\Http\Requests\CreateEventRequest;
use Illuminate\Http\Request;

class eventsController extends Controller
{
//_____________________ Add Event
    function add_event(CreateEventRequest $req){
        $user_id=$req->user()->id;
        $reqest=collect($req)->toArray();
        $reqest['user_id']=$user_id;
        $event=eventRepository::create($reqest);
        return response([
            'status'=>'success',
            'message'=>'Your event is saved successfully',
            'event'=>$event
        ],200);
    }
//_____________________ User Events List
    function event_list(Request $req){
        $user_id=$req->user()->id;
        $events=eventRepository::list($user_id);
        return response([
            'status'=>'success',
            'event'=>$events
        ],200);
    }
}
