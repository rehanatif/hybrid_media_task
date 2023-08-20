<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EventAttendy extends Model
{
    use HasFactory;

    public function addEventAttendies($object){

          DB::transaction(function() use($object){

            $user_ids = explode(',',$object['user_ids']);

            foreach($user_ids as $user_id){

                $data[] = [
                    'event_id'      => $object['event_id'],

                    'user_id'       => $user_id,

                    'created_at'    => now(),

                    'updated_at'    => now(),
                ];
            }

           $is_insert = EventAttendy::insert($data);                   

            return with($is_insert);
        });
    }

    public function updateEventAttendies($object){

          DB::transaction(function() use($object){

            $user_ids = explode(',',$object['user_ids']);

            $this->deleteAttendeesByUserIds($user_ids);            

           $is_insert = $this->addEventAttendies($object);                   

            return with($is_insert);
        });
    }

    public function deleteAttendeesByUserIds($user_ids){

        return DB::transaction(function() use($user_ids){

            $is_deleted = EventAttendy::whereIn('user_id',$user_ids)->delete();

            return with($user_ids);
        });

    }
}
