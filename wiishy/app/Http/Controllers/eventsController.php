<?php

namespace App\Http\Controllers;

use App\Repositories\eventRepository;
use App\Http\Requests\CreateEventRequest;
use App\Http\Resources\eventListResource;
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
    function event_user(Request $req){
        $user_id=$req->user()->id;
        $events=eventRepository::list($user_id);
        $event_list=eventListResource::collection($events);
        
        return response([
            'status'=>'success',
            'event'=>$event_list
        ],200);
    }

    function event_list(Request $req){
        $events=eventRepository::events();    
        return response([
            'status'=>'success',
            'event'=>$events
        ],200);
    }

    function relationship_list(Request $req){
        $relation=eventRepository::relationships();  
        return response([
            'status'=>'success',
            'event'=>$relation
        ],200);
    }
}