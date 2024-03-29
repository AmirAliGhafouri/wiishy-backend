<?php

namespace App\Http\Controllers;

use App\Repositories\eventRepository;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\event_followingbirthdayResource;
use App\Http\Resources\eventListResource;
use App\Repositories\followRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * This class is for events management
 */
class eventsController extends Controller
{
    /**
     * Add new event
     * 
     * @param \App\Http\Requests\CreateEventRequest $req 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function create(CreateEventRequest $req)
    {
        // get user_id from Request
        $user_id = $req->user()->id;
        $reqest = collect($req->validate())->toArray();

        // Add user_id to request
        $reqest['user_id'] = $user_id;
        $event = eventRepository::create($reqest);
        return response([
            'status' => 'success',
            'message' => 'Your event is saved successfully',
            'event' => $event
        ], 200);
    }

    /**
     * Update an event
     * 
     * @param \App\Http\Requests\UpdateEventRequest $req
     * @param int $id event_id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateEventRequest $req)
    {
        // Checking that the request is not empty
        if (!$req->all()) {
            return response([
                'status' => 'Error',
                'message' => 'Empty request'
            ], 400);
        }

        // get user_id
        $user_id = $req->user()->id;

        // checking that event is exist
        $event = eventRepository::get($req->event_id, $user_id);
        if (!$event) {
            return response([
                'status' => 'Error',
                'message' => 'Event not found'
            ], 400);
        }

        // remove empty requests
        $request = collect($req->validated())->filter(function($item){
            return $item != null;
        })->toArray();

        eventRepository::update($req->event_id, $request);
        $new_event = eventRepository::get($req->event_id, $user_id);
        return response([
            'status' => 'success',
            'message' => 'The event is updated successfully',
            'event' => $new_event
        ], 200);
    }

    /**
     * remove an event
     * 
     * @param \Illuminate\Http\Request $req
     * @param int $id event_id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function remove(Request $req)
    {
        $user_id = $req->user()->id;

        // checking that event is exist
        $event = eventRepository::get($req->event_id, $user_id);
        if (!$event) {
            return response([
                'status' => 'Error',
                'message' => 'Event not found'
            ], 400);
        }

        eventRepository::destroy($req->event_id);

        return response([
            'status' => 'success',
            'event' => 'The event is removed successfully'
        ], 200);
    }

    /**
     * List of user defined events
     * 
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function list(Request $req)
    {
        $user_id = $req->user()->id;

        // get list of users events
        $events = eventRepository::list($user_id);

        // prepare event_list and add relation, type, and remaining days
        $event_list = eventListResource::collection($events);
        
        return response([
            'status' => 'success',
            'event' => $event_list
        ], 200);
    }

    /**
     * Events with deadlines less than 30 days
     * 
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function userNearEvents(Request $req)
    {
        $user_id = $req->user()->id;

        // get list of users events
        $events = eventRepository::list($user_id);
        
        // prepare event_list and add relation, type, and remaining days
        $event_list = collect(EventListResource::collection($events));

        // filter events with deadline less than 30 days
        $filtered_events = $event_list->filter(function ($eventResource) {
            $remainingDays = eventRepository::remaining_days($eventResource['event_date']);
            return $remainingDays < 30;
        });
        $filtered_events_array = $filtered_events->toArray();
        $sorted_events_array = $filtered_events->sortBy('remaining_days');
        
        return response([
            'status' => 'success',
            'event' => $sorted_events_array
        ], 200);
    }

    /**
     * details of an event
     * 
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function eventDetail(Request $req)
    {
        $user_id = $req->user()->id;
        $event = eventRepository::detail($user_id, $req->event_id);
        if (!$event) {
            return response([
                'status' => 'Error',
                'message' => 'not found'
            ], 400);
        }
                    
        return response([
            'status' => 'success',
            'message' => $event
        ], 200);
    }

    /**
     * Events and followings Birthday
     *  
     * @param \Illuminate\Http\Request $req
     * @return Illuminate\Http\Respons
     */ 
    public function followingsBirthday(Request $req)
    {
        $user_id = $req->user()->id;

        // events of user
        $events = eventRepository::list($user_id);
        $event_list = collect(EventListResource::collection($events));

        // filter events with deadline less than 30 days
        $filtered_events = $event_list->filter(function ($eventResource) {
            $remainingDays = eventRepository::remaining_days($eventResource['event_date']);
            return $remainingDays < 30;
        });
        $filtered_events_array = $filtered_events->toArray();

        // Add flad is_my_event
        foreach ($filtered_events as $item) {
            $item['is_my_event'] = true;
        }

        // list of followings
        $followings = followRepository::follow_list($user_id, 'userfollows.follow_id', 'userfollows.user_id');

        // filter followings birthday with deadline less than 30 days
        $following_B_events = $followings->filter(function ($user_birthday) {
            $fb = eventRepository::remaining_days($user_birthday->user_birthday);
            return $fb < 30;
        });
        
        // Add event_type to followingbirthday
        foreach($following_B_events as $following) {
            $following->event_type = 1;
            $following->event_date = $following->user_birthday;
            $following->is_my_event = false;
        }
        
        $merged_events = $filtered_events->merge($following_B_events);
        $sorted_events = $merged_events->sortBy('remaining_days');
        $events_fb = event_followingbirthdayResource::collection($sorted_events);
        
        return response([
            'status' => 'success',
            'events' => $events_fb,
        ], 200); 

    }

    /**
     * list of events types from DataBase
     */
    public function eventList(Request $req)
    {
        $events = eventRepository::events();    
        return response([
            'status' => 'success',
            'event' => $events
        ], 200);
    }

    /**
     * list of relationship types from DataBase 
     */
    public function relationshipList(Request $req)
    {
        $relation = eventRepository::relationships();  
        return response([
            'status' => 'success',
            'event' => $relation
        ], 200);
    }
}
