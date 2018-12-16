<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use App\Services\ParticipantService;

class EventController extends Controller
{

    protected $eventService;
    protected $participantService;

    public function __construct(
        EventService $eventService,
        ParticipantService $participantService
    ){
        $this->eventService = $eventService;
        $this->participantService = $participantService;
	}

    /**
     * 開催予定のイベント一覧を返却
     *
     * @return void
     */
    public function events()
    {
        $responseEvents = [];

        $events = $this->eventService->getHeldBeforeEvents();
        foreach ($events as $key => $event) {
            $responseEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'event_date' => $event->eventDate,
                'event_page_url' => $event->eventPageUrl,
                'participants' => $event->participants,
            ];
        }

        return $responseEvents;
    }

    public function eventDetail($eventId)
    {
        $event = $this->eventService->getEventById($eventId);
        $participants = $this->participantService->getParticipantsByEventId($eventId);

        $frames = [];
        $frameNames = [];

        foreach ($participants as $key => $participant) {

            if(array_search($participant->frame, $frameNames) === false)
            {
                $frameNames[] = $participant->frame;
                $frames[] = [
                    'name' => $participant->frame,
                    'members' => []
                ];
            }

            $key = array_search($participant->frame, $frameNames);

            $frames[$key]['members'][] = [
                'name' => $participant->name,
                'icon_url' => $participant->iconUrl
            ];
        }

        return [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'event_date' => $event->eventDate,
                'event_page_url' => $event->eventPageUrl,
                'participants' => $event->participants,
            ],
            'frames' => $frames
        ];
    }
}
