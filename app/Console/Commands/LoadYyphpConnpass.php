<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use phpQuery;
use Illuminate\Console\Command;
use App\Domain\Model\Event;

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $html = file_get_contents("http://web/connpass.html");
        // $dom = phpQuery::newDocument($html);
        // dd($dom->find('.group_event_list')->text());

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

        //各イベント日の参加者情報取得
        foreach($events as $key => $event)
        {
            $html = file_get_contents("http://web/participation_connpass.htm");

            $participationDom = phpQuery::newDocument($html)->find(".participation_table_area");
            foreach ($eventsDom as $key => $eventDom) {
                $index = $key;
                $userName = phpQuery::newDocument($html)
                            ->find(".participation_table_area:eq($index)")
                            ->find('.participants_table')
                            ->find('tbody')
                            ->find('tr')
                            ->find('.user')
                            ->find('.user_info')
                            ->find('.display_name')
                            ->find('a')
                            ->text();
                var_dump($userName);
            }
        }
    }
}