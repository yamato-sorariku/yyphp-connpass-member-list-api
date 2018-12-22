<?php

namespace App\Services;

use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\ParticipantRepositoryInterface;


class ConnpassService
{

    /**
     * YYPHPのConnpassトップページを取得する
     *
     * @return void
     */
    public function loadYYPHPConnpassPage()
    {
        return file_get_contents("https://yyphp.connpass.com/");
    }

    /**
     * YYPHPのConnpass イベント参加者一覧ページを取得する
     *
     * @param [type] $eventId
     * @return void
     */
    public function loadYYPHPConnpassEventPage($eventId)
    {
        return file_get_contents("https://yyphp.connpass.com/event/$eventId/participation/");
    }
}