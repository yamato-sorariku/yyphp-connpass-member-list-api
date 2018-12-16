<?php

namespace App\Services;

use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\ParticipantRepositoryInterface;


class EventService
{

    protected $eventRepo;
    protected $participantRepo;

    public function __construct(
        EventRepositoryInterface $eventRepositoryInterface,
        ParticipantRepositoryInterface $participantRepositoryInterface
    ){
        $this->eventRepo = $eventRepositoryInterface;
        $this->participantRepo = $participantRepositoryInterface;
	}


    /**
     * 開催前のイベント情報を取得する
     *
     * @return void
     */
    public function getHeldBeforeEvents()
    {
        return $this->eventRepo->events();
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
}