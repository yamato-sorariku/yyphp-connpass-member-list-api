<?php 
namespace App\Persistence;

use  App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\Event;

class FileCacheEventRepository implements EventRepositoryInterface
{
    public function events()
    {

        $event1 = new Event();
        $event1->id = '110977';
        $event1->eventDate = '2018-10-10';
        $event1->title = '#YYPHP #64【PHPの情報交換・ワイワイ話そう・仲間作り・ゆるめ・にぎやかめ】';
        $event1->eventPageUrl = 'https://yyphp.connpass.com/event/110977/';
        $event1->participants = 6;

        $event2 = new Event();
        $event2->id = '110978';
        $event2->eventDate = '2018-10-11';
        $event2->title = '#YYPHP #64【PHPの情報交換・ワイワイ話そう・仲間作り・ゆるめ・にぎやかめ】';
        $event2->eventPageUrl = 'https://yyphp.connpass.com/event/110978/';
        $event2->participants = 6;

        $event3 = new Event();
        $event3->id = '110979';
        $event3->eventDate = '2018-10-12';
        $event3->title = '#YYPHP #64【PHPの情報交換・ワイワイ話そう・仲間作り・ゆるめ・にぎやかめ】';
        $event3->eventPageUrl = 'https://yyphp.connpass.com/event/110979/';
        $event3->participants = 6;

        return [
            $event1,
            $event2,
            $event3
        ];
    }

    public function eventById($id)
    {
        $event1 = new Event();
        $event1->id = $id;
        $event1->eventDate = '2018-10-10';
        $event1->title = '#YYPHP #64【PHPの情報交換・ワイワイ話そう・仲間作り・ゆるめ・にぎやかめ】';
        $event1->eventPageUrl = 'https://yyphp.connpass.com/event/110977/';
        $event1->participants = 6;

        return $event1;
    }
}