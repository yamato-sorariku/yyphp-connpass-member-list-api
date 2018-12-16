<?php 
namespace App\Persistence;

use Illuminate\Support\Facades\Cache;
use App\Domain\Model\ParticipantRepositoryInterface;
use App\Domain\Model\Participant;

class FileCacheParticipantRepository implements ParticipantRepositoryInterface
{

    const CACHE_KEY_PARTICIPANTS = 'participants';

    public function participantsByEventId($eventId)
    {
        $participants = Cache::get(self::CACHE_KEY_PARTICIPANTS);

        $resultParticipants = [];

        foreach($participants as $key => $participant)
        {
            if($participant->eventId === $eventId)
            {
                $resultParticipants[] = $participant;
            }
        }

        
        return $resultParticipants;
    }

    public function saveParticipants($participants)
    {
        Cache::forever(self::CACHE_KEY_PARTICIPANTS, $participants);
    }
}