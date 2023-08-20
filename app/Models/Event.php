<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventAttendy;
use App\Models\User;
use DB;

class Event extends Model
{
    use HasFactory;

    public function addEvent($object){

        return DB::transaction(function() use($object){

            $event = new Event;

            $event->subject = $object['subject'];

            $event->start_date    = $object['start_date'];

            $event->end_date    = $object['end_date'];

            $event->save();

            if(isset($event->id)){

                $event_attendy = new EventAttendy;

                $object['event_id'] = $event->id;

                $event_attendy = $event_attendy->addEventAttendies($object);
            } 
            
            // Observer listening on this Event Create

            return with($event);
        });
    }
    
    public function updateEvent($object){

        return DB::transaction(function() use($object){

            $event = $this->getEventById($object['event_id']);

            if(isset($event->id))
            {
                $event->subject = $object['subject'];
    
                $event->start_date      = $object['start_date'];

                $event->end_date        = $object['end_date'];
    
                $event->save();
            }


            if(isset($event->id)){

                $event_attendy = new EventAttendy;

                $object['event_id'] = $event->id;

                $event_attendy = $event_attendy->updateEventAttendies($object);
            }

           

            return with($event);
        });
    }

    public function deleteEvent($id)
    {
        return DB::transaction(function() use($id){

            $event = $this->getEventById($id);

            if(isset($event->id))
            {              
                $user_ids = $event->users->pluck('id')->toArray();

                $event->delete();

                $event_attendy = new EventAttendy;                

                $event_attendy = $event_attendy->deleteAttendeesByUserIds($user_ids);
            }            

            return with($event);
        });
    }

    public function getEventList()
    {
        return Event::orderBy('id','DESC');
    }
   
    public function getEventById($id)
    {
        return Event::where('id',$id)->first();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_attendies', 'event_id', 'user_id');
    }    
}
