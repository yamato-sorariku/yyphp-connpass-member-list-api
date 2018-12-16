<?php 
namespace App\Domain\Model;

interface EventRepositoryInterface
{
    public function events();
    public function eventById($id);
    public function saveEvents($events);
    
}