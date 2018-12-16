<?php 
namespace App\Persistence;

use  App\Domain\Model\ParticipantRepositoryInterface;
use App\Domain\Model\Participant;

class FileCacheParticipantRepository implements ParticipantRepositoryInterface
{
    public function participantsByEventId($eventId)
    {
        $participant1 = new Participant();
        $participant1->frame = 'スタッフ枠';
        $participant1->name = 'スタッフ枠 1';
        $participant1->iconUrl = 'https://connpass-tokyo.s3.amazonaws.com/thumbs/57/4c/574c94930d8e233ca312c0a060114ab6.png';

        $participant2 = new Participant();
        $participant2->frame = '現地参加';
        $participant2->name = '現地参加 1';
        $participant2->iconUrl = 'https://connpass-tokyo.s3.amazonaws.com/thumbs/57/4c/574c94930d8e233ca312c0a060114ab6.png';

        $participant3 = new Participant();
        $participant3->frame = 'リモート枠';
        $participant3->name = 'リモート枠 1';
        $participant3->iconUrl = 'https://connpass-tokyo.s3.amazonaws.com/thumbs/57/4c/574c94930d8e233ca312c0a060114ab6.png';


        return [
            $participant1,
            $participant2,
            $participant3
        ];
    }
}