<?php

namespace App\Services;

use phpQuery;
use Carbon\Carbon;
use App\Domain\Model\Event;
use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\ParticipantRepositoryInterface;
use App\Services\ConnpassService;


class EventService
{

    protected $eventRepo;
    protected $connpassService;

    public function __construct(
        EventRepositoryInterface $eventRepositoryInterface,
        ConnpassService $connpassService
    ){
        $this->eventRepo = $eventRepositoryInterface;
        $this->connpassService = $connpassService;
	}


    /**
     * 開催前のイベント情報を取得する
     *
     * @return void
     */
    public function getHeldBeforeEvents()
    {
        $events = $this->eventRepo->events();
        
        if( is_array($events) )
        {
            return $events;
        }
        return [];
    }

    /**
     * 引数のイベント情報を取得する
     *
     * @param [type] $eventId
     * @return void
     */
    public function getEventById($eventId)
    {
        return $this->eventRepo->eventById($eventId);
    }

    /**
     * イベント一覧を保存する
     *
     * @param [type] $events
     * @return void
     */
    public function saveEvents($events)
    {
        $this->eventRepo->saveEvents($events);
    }

    /**
     * Connpassからイベント一覧を取得する
     *
     * @return void
     */
    public function loadEventsFromConnpass()
    {
        $topPageHtml = $this->connpassService->loadYYPHPConnpassPage();
        $events = [];

        $eventsDom = phpQuery::newDocument($topPageHtml)->find(".group_event_list");
        foreach ($eventsDom as $key => $eventDom) {
            $index = $key;
            $status = phpQuery::newDocument($topPageHtml)
                        ->find(".group_event_list:eq($index)")
                        ->find('.group_event_inner')
                        ->find('.schedule')
                        ->find('.label_status_event')
                        ->text();

            if( ! ($status === '開催前' || $status === '開催中') )
            {
                continue;
            }

            $eventDateUtc = phpQuery::newDocument($topPageHtml)
                ->find(".group_event_list:eq($index)")
                ->find('.group_event_inner')
                ->find('.schedule')
                ->find('.dtstart')
                ->find('.value-title')
                ->attr("title");

            $eventDateDt = new Carbon($eventDateUtc);
            $eventDateDt->setTimezone('Asia/Tokyo');

            $eventPageUrl = phpQuery::newDocument($topPageHtml)
            ->find(".group_event_list:eq($index)")
            ->find('.thumb_area')
            ->find('.image_link')
            ->attr('href');

            $title = phpQuery::newDocument($topPageHtml)
            ->find(".group_event_list:eq($index)")
            ->find('.thumb_area')
            ->find('.image_link')
            ->find('img')
            ->attr("alt");

            $participants = phpQuery::newDocument($topPageHtml)
            ->find(".group_event_list:eq($index)")
            ->find('.group_event_inner')
            ->find('.event_participants')
            ->find('.amount')
            ->text();

            $urlParsed = explode("/", $eventPageUrl);
            $id = $urlParsed[count($urlParsed) - 2];

            $event = new Event();
            $event->id = $id;
            $event->eventDate = $eventDateDt->format('Y年m月d日 H時i分');
            $event->title = $title;
            $event->eventPageUrl = $eventPageUrl;
            $event->participants = $participants;

            $events[] = $event;
                
        }

        return $events;
    }
}