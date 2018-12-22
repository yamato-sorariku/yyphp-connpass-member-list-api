<?php

namespace App\Services;

use phpQuery;
use App\Domain\Model\Participant;
use App\Domain\Model\ParticipantRepositoryInterface;

class ParticipantService
{

    protected $participantRepo;
    protected $connpassService;

    public function __construct(
        ParticipantRepositoryInterface $participantRepositoryInterface,
        ConnpassService $connpassService
    ){
        $this->participantRepo = $participantRepositoryInterface;
        $this->connpassService = $connpassService;
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

    public function saveParticipants($participants)
    {
        $this->participantRepo->saveParticipants($participants);
    }

    public function loadParticipantsFromConnpass($eventId)
    {
        $html = $this->connpassService->loadYYPHPConnpassEventPage($eventId);

        //種別ごと
        $participationDoms = phpQuery::newDocument($html)->find(".participation_table_area");

        foreach ($participationDoms as $key => $participationDom) {

            $frame = [];

            $index = $key;
            $frameName = phpQuery::newDocument($html)
            ->find(".participation_table_area:eq($index)")
            ->find('.common_table')
            ->find('thead')
            ->find('tr')
            ->find('th')
            ->find('.label_ptype_name')
            ->text();

            $frame['name'] = $frameName;

            $userDoms = phpQuery::newDocument($html)
            ->find(".participation_table_area:eq($index)")
            ->find('tbody')
            ->find('tr');

            foreach ($userDoms as $userIndex => $userDom) {

                $participant = new Participant();

                $userName = phpQuery::newDocument($html)
                            ->find(".participation_table_area:eq($index)")
                            ->find('tbody')
                            ->find("tr:eq($userIndex)")
                            ->find('.user_info')
                            ->find('.display_name')
                            ->find('a')
                            ->text();

                $iconUrl = phpQuery::newDocument($html)
                            ->find(".participation_table_area:eq($index)")
                            ->find('tbody')
                            ->find("tr:eq($userIndex)")
                            ->find('.user_info')
                            ->find('.image_link')
                            ->find('img')
                            ->attr("src");

                $participant->eventId = $event->id;
                $participant->name = $userName;
                $participant->iconUrl = $iconUrl;
                $participant->frame = $frameName;

                $participants[] = $participant;
            }
        }

        return $participants;
    }

}