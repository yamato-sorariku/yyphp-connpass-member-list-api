<?php 
namespace App\Domain\Model;

interface ParticipantRepositoryInterface
{
    public function participantsByEventId($eventId);
}