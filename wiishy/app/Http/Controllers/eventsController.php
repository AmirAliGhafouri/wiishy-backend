<?php

namespace App\Http\Controllers;

use App\Repositories\eventRepository;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\eventListResource;
use App\Http\Resources\followingsBirthdayResource;
use App\Repositories\followRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class eventsController extends Controller
{
//_____________________ Add Event
    function add_event(CreateEventRequest $req){
        $user_id=$req->user()->id;
        $reqest=collect($req->validate())->toArray();
        $reqest['user_id']=$user_id;
        $event=eventRepository::create($reqest);
        return response([
            'status'=>'success',
            'message'=>'Your event is saved successfully',
            'event'=>$event
        ],200);
    }

//_____________________ Update Event
    function update_event(UpdateEventRequest $req){
        if(!$req->all()){
            return response([
                'status'=>'Error',
                'message'=>'Empty request'
            ],400);
        }
        $user_id=$req->user()->id;
        $event=eventRepository::get($req->event_id,$user_id);
        if(!$event){
            return response([
                'status'=>'Error',
                'message'=>'Event not found'
            ],400);
        }

        $request =collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();

        eventRepository::update($req->event_id,$request);
        $new_event=eventRepository::get($req->event_id,$user_id);
        return response([
            'status'=>'success',
            'message'=>'The event is updated successfully',
            'event'=>$new_event
        ],200);
    }

//_____________________ Remove an Event
    function event_remove(Request $req){
        $user_id=$req->user()->id;
        $event=eventRepository::get($req->event_id,$user_id);
        if(!$event){
            return response([
                'status'=>'Error',
                'message'=>'Event not found'
            ],400);
        }
        eventRepository::destroy($req->event_id);
        return response([
            'status'=>'success',
            'event'=>'The event is removed successfully'
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

//_____________________ Events with deadlines less than 20 days
    function user_near_events(Request $req){
        $user_id=$req->user()->id;
        $events=eventRepository::list($user_id);
        
        $event_list = collect(EventListResource::collection($events));
        $filtered_events = $event_list->filter(function ($eventResource) {
            $remainingDays = eventRepository::remaining_days($eventResource['event_date']);
            return $remainingDays < 30;
        });
        $filtered_events_array = $filtered_events->toArray();
        
        return response([
            'status'=>'success',
            'event'=>$filtered_events_array
        ],200);
    }

//_____________________ Events detail
    function event_detail(Request $req){
        $user_id=$req->user()->id;
        $event=eventRepository::detail($user_id,$req->event_id);
        if(!$event){
            return response([
                'status'=>'Error',
                'message'=>'not found'
            ],400);
        }
                    
        return response([
            'status'=>'success',
            'message'=>$event
        ],200);
    }

//_____________________ Events and followings Birthday
    function followings_birthday(Request $req){
        $user_id=$req->user()->id;
        $events=eventRepository::list($user_id);
        $event_list=eventListResource::collection($events);
        $followings=followRepository::follow_list($user_id,'userfollows.follow_id','userfollows.user_id');
        $followings_list = collect($followings);
        $following_B_events = $followings_list->filter(function ($user_birthday) {
            $event_date= Carbon::create( $user_birthday['event_date']);
            $currentDate = Carbon::now();
            $eventThisYear = $event_date->copy()->year($currentDate->year);
            if ($currentDate > $eventThisYear) {
                $eventThisYear->addYear();
            }
            $fb=now()->diffInDays(Carbon::parse($eventThisYear));
            return $fb < 30;
        });
        $followings_birthday=followingsBirthdayResource::collection($following_B_events);
        
        return response([
            'status'=>'success',
            'events'=>$event_list,
            'followings_birthday'=>$followings_birthday
        ],200);
    }

//_____________________ Events name List
    function event_list(Request $req){
        $events=eventRepository::events();    
        return response([
            'status'=>'success',
            'event'=>$events
        ],200);
    }

//_____________________ relationships name List
    function relationship_list(Request $req){
        $relation=eventRepository::relationships();  
        return response([
            'status'=>'success',
            'event'=>$relation
        ],200);
    }
}
