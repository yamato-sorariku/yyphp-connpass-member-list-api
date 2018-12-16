<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use phpQuery;
use Illuminate\Console\Command;
use App\Domain\Model\Event;
use App\Domain\Model\Participant;
use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\ParticipantRepositoryInterface;

class LoadYyphpConnpass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:loadYyphpConnpass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EventRepositoryInterface $eventRepositoryInterface,
        ParticipantRepositoryInterface $participantRepositoryInterface
    )
    {
        parent::__construct();

        $this->eventRepo = $eventRepositoryInterface;
        $this->participantRepo = $participantRepositoryInterface;
    }

    protected $eventRepo;
    protected $participantRepo;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $html = file_get_contents("https://yyphp.connpass.com/");
        $events = [];

        $eventsDom = phpQuery::newDocument($html)->find(".group_event_list");
        foreach ($eventsDom as $key => $eventDom) {
            $index = $key;
            $status = phpQuery::newDocument($html)
                        ->find(".group_event_list:eq($index)")
                        ->find('.group_event_inner')
                        ->find('.schedule')
                        ->find('.label_status_event')
                        ->text();

            if($status !== '開催前')
            {
                continue;
            }

            $eventDateUtc = phpQuery::newDocument($html)
                ->find(".group_event_list:eq($index)")
                ->find('.group_event_inner')
                ->find('.schedule')
                ->find('.dtstart')
                ->find('.value-title')
                ->attr("title");

            $eventDateDt = new Carbon($eventDateUtc);
            $eventDateDt->setTimezone('Asia/Tokyo');

            $eventPageUrl = phpQuery::newDocument($html)
            ->find(".group_event_list:eq($index)")
            ->find('.thumb_area')
            ->find('.image_link')
            ->attr('href');

            $title = phpQuery::newDocument($html)
            ->find(".group_event_list:eq($index)")
            ->find('.thumb_area')
            ->find('.image_link')
            ->find('img')
            ->attr("alt");

            $participants = phpQuery::newDocument($html)
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

        $participants = [];

        //各イベント日の参加者情報取得
        foreach($events as $eventKey => $event)
        {
            $html = file_get_contents("https://yyphp.connpass.com/event/$event->id/participation/");

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
        }

        //永続化
        $this->eventRepo->saveEvents($events);
        $this->participantRepo->saveParticipants($participants);
    }
}
