<?php

namespace App\Services;

use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\ParticipantRepositoryInterface;


class EventService
{

    protected $eventRepo;

    public function __construct(
        EventRepositoryInterface $eventRepositoryInterface
    ){
        $this->eventRepo = $eventRepositoryInterface;
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
}