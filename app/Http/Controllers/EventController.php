<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Google\Client;
use Google\Service\Oauth2;;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use DataTables;

class EventController extends Controller
{
    
    public $event;
    public function __construct(Event $event)
    {
        $this->event = $event;       
    }
    
    public function getValidation($validation = []){

        $validation = [
            'subject'   => 'required|string',
            'start_date'      => 'required|date||after:now',
            'end_date'      => 'required|date||after:start_date',
            'email'     => 'required|array|between:0,3',
            'email.*'   => 'required|email|distinct',
        ]+$validation;

        return $validation;
    }
    
    public function index(Request $request){
        
        if($request->isMethod('post')){
            
            $count     = $request->start;

            $event_list = $this->event->getEventList();

            $value = $request->search['value'];

            return DataTables::of($event_list)

            // ->filterColumn('name', function($query, $value){

            //     $query->where('name','ilike','%'.$value.'%');

            // })
            ->addColumn('count',function($rows) use (&$count)
                {
                    return ++$count;
                })
            ->addColumn('attendies',function($rows) use (&$count)
            {
                return view('events.actions.event_attendies',['rows'=>$rows])->render();                
            })
            ->addColumn('action',function($rows) use (&$count)
            {
                return view('events.actions.event_action',['rows'=>$rows])->render();                
            })
            ->rawColumns(['count','action','attendies'])
            ->make(true);
        }
        else{
    
            return view('events.manage_events');
        }
    }
    
    public function addEvent(Request $request){

        try{

            if($request->isMethod('post')){
                
                $request->validate($this->getValidation());

                $form_collect = $request->input();

                $event = $this->event->addEvent($form_collect);

                parent::setResponse(false,'Something went wrong.');

                if(isset($event->id)){
                    // $this->oauth();
                    // $this->store($form_collect);

                    parent::setResponse(true,'Event add successfully.');
                }
            }
            else{
        
                return view('events.modals.add_event')->render();
            }
        }
        catch(\Throwable $e){
            
            parent::setResponse(false,$e->getMessage());

        }

        return parent::getResponse();

    }
   
    public function updateEvent(Request $request){

        try{
            $event = $this->event->getEventById($request->event_id);

            if(isset($event->id))
            {
                if($request->isMethod('post')){
                    
                    $request->validate($this->getValidation(['event_id'=>'required']));
    
                    $form_collect = $request->input();
    
                    $event = $this->event->updateEvent($form_collect);
    
                    parent::setResponse(false,'Something went wrong.');
    
                    if(isset($event->id)){
    
                        parent::setResponse(true,'Event update successfully');
                    }
    
                }
                else{
            
                    return view('events.modals.update_event',[
                        'event' => $event,
                    ])->render();
                }
            }
            else{
                parent::setResponse(false,'Event not fount');
            }

        }
        catch(\Throwable $e){
            
            parent::setResponse(false,$e->getMessage());

        }

        return parent::getResponse();

    }

    public function deleteEvent(Request $request){

        try{
            $form_collect = $request->input();

            $event = $this->event->deleteEvent($form_collect['event_id']);
    
            parent::setResponse(false,'Something went wrong.');
    
            if(isset($event->id)){
    
                parent::setResponse(true,'Event Delete Successfully');
            }
        }
        catch(Exception $e){
            parent::setResponse(false,$e->getMessage());
        }

        return parent::getResponse();
    }
}
