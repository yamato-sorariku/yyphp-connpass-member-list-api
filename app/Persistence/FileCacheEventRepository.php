<?php 
namespace App\Persistence;

use Illuminate\Support\Facades\Cache;
use App\Domain\Model\EventRepositoryInterface;
use App\Domain\Model\Event;

class FileCacheEventRepository implements EventRepositoryInterface
{

    const CACHE_KEY_EVENTS = 'events';

    public function events()
    {
        return Cache::get(self::CACHE_KEY_EVENTS);
    }

    public function eventById($eventId)
    {
        $events = $this->events();

        foreach($events as $key => $event)
        {
            if($event->id === $eventid)
            {
                return $event;
            }
        }

        return null;
    }

    public function saveEvents($events)
    {
        Cache::forever(self::CACHE_KEY_EVENTS, $events);
    }
}