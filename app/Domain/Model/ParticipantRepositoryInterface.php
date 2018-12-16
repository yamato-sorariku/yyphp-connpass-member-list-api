<?php 
namespace App\Domain\Model;

interface ParticipantRepositoryInterface
{
    public function participantsByEventId($eventId);
    public function saveParticipants($participants);
}