<?php

namespace App\Observers;

use App\Models\Event;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event as GEvent;
use Google\Service\Calendar\EventReminder;
use Google\Service\Calendar\EventReminders;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        $this->createGoogleEvent($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $this->updateGoogleEvent($event);
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        $this->deleteEvent($event);
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }

    public function getClient(){

        $client = new Client();
        $client->setAuthConfig(storage_path('app\google_credentials\rehan-365505-7fdabec46672.json'));
        $client->addScope(Calendar::CALENDAR_EVENTS);

        return $client;
    }
    public function createGoogleEvent($event)
    {
        // Load the Google API client
        $description = $event->subject;

        $attendees  = $event->users->pluck('email')->toArray();
        
        $client = $this->getClient();

        // Authenticate and create service
        $service = new Calendar($client);

        // Create an event
        $g_event = new GEvent([
            'summary' => 'Meeting Title',
            'description' => $description,
            'start' => [
                'dateTime' => $event->start_date,
                'timeZone' => 'Asia/Karachi',
            ],
            'end' => [
                'dateTime' => $event->end_date,
                'timeZone' => 'Asia/Karachi',
            ],
            'maxAttendees' => 2, // Adjust this value as needed
            'attendees' => [
                // ['email' => 'engr.rehan.atif@gmail.com'],
                // ['email' => 'onlyskyismylimit@gmail.com'],
                // Add more attendees as needed
                $attendees,
            ],
        ]);

         // Create event reminders
         $reminder = new EventReminder([
            'method' => 'email',
            'minutes' => 15, // Notification will be sent 15 minutes before the event
        ]);

        $reminders = new EventReminders([
            'useDefault' => false,
            'overrides' => [$reminder],
        ]);

        $g_event->setReminders($reminders);

        $calendarId = 'primary'; // Use 'primary' for the user's primary calendar

        // Insert the event
        $createdEvent = $service->events->insert($calendarId, $g_event);

        $event->google_event_id = $createdEvent->getId();

        $event->update();

        // You can handle the response as needed
        return response()->json([
            'message' => 'Event created successfully.',
            'event_id' => $createdEvent->getId(),
        ]);
    }

    public function updateGoogleEvent($event)
    {
        // Load the Google API client
        $client = $this->getClient();

        // Authenticate and create service
        $service = new Calendar($client);

        // Retrieve the existing event
        $calendarId = 'primary'; // Use 'primary' for the user's primary calendar
        $existingEvent = $service->events->get($calendarId, $event->google_event_id);

        // Modify the event properties
        $existingEvent->setSummary('Updated Meeting Title');
        $existingEvent->setDescription($event->subject);
        $existingEvent->setStart(['dateTime' => $event->start_date, 'timeZone' => 'Asia/Karachi']);
        $existingEvent->setEnd(['dateTime' => $event->end_date, 'timeZone' => 'Asia/Karachi']);

        // Update event reminders
        $reminder = new EventReminder(['method' => 'email', 'minutes' => 30]);
        $reminders = new EventReminders(['useDefault' => false, 'overrides' => [$reminder]]);
        $existingEvent->setReminders($reminders);

        // Send the update request
        $updatedEvent = $service->events->update($calendarId, $event->google_event_id, $existingEvent);

        // You can handle the response as needed
        return response()->json([
            'message' => 'Event updated successfully.',
            'updated_event_id' => $updatedEvent->getId(),
        ]);
    }

    public function deleteEvent($event)
    {
        // Load the Google API client
        $client = $this->getClient();       

        // Authenticate and create service
        $service = new Calendar($client);

        // Delete the event
        $calendarId = 'primary'; // Use 'primary' for the user's primary calendar
        $service->events->delete($calendarId, $event->google_event_id);

        // You can handle the response as needed
        return response()->json([
            'message' => 'Event deleted successfully.',
        ]);
    }
}
