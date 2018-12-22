<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventService;
use App\Services\ParticipantService;

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
    public function __construct(
        EventService $eventService,
        ParticipantService $participantService
    )
    {
        parent::__construct();

        $this->eventService = $eventService;
        $this->participantService = $participantService;
    }

    protected $eventService;
    protected $participantService;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info('Begin YYPHP load from Connpass');
        
        $events = $this->eventService->loadEventsFromConnpass();

        $participants = [];

        //各イベント日の参加者情報取得
        foreach($events as $eventKey => $event)
        {
            $participants = array_merge
            (
                $participants,
                $this->participantService->loadParticipantsFromConnpass($event->id)
            );
        }

        //永続化
        $this->eventService->saveEvents($events);
        $this->participantService->saveParticipants($participants);

        info('End YYPHP load from Connpass');
    }
}
