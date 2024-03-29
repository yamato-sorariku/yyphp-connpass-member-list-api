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
        info(Cache::get(self::CACHE_KEY_EVENTS));
        return Cache::get(self::CACHE_KEY_EVENTS);
    }

    public function eventById($eventId)
    {
        $events = $this->events();
        info($events);

        if( ! is_array($events) )
        {
            return null;
        }


        foreach($events as $key => $event)
        {
            if($event->id === $eventId)
            {
                return $event;
            }
        }

        return null;
    }

    public function saveEvents($events)
    {
        info($events);
        Cache::forever(self::CACHE_KEY_EVENTS, $events);
    }
}