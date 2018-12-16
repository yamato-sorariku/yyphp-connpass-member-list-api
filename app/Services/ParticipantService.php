<?php

namespace App\Services;

use App\Domain\Model\ParticipantRepositoryInterface;

class ParticipantService
{

    protected $participantRepo;

    public function __construct(
        ParticipantRepositoryInterface $participantRepositoryInterface
    ){
        $this->participantRepo = $participantRepositoryInterface;
	}

    /**
     * 引数のイベントに参加予定の参加者を返却する
     *
     * @param [type] $eventId
     * @return void
     */
    public function getParticipantsByEventId($eventId)
    {
        return $this->participantRepo->participantsByEventId($eventId);
    }

}