<?php

namespace Tests\Feature\Http\Controllers\Apis;;

use Tests\TestCase;

class EventControllerTest extends TestCase
{
    public function test_events_200応答すること()
    {
        $response = $this->get('/api/events');
        $response->assertStatus(200);
    }

    public function test_events_detail_200応答すること()
    {
        $response = $this->get('/api/events/12345');
        $response->assertStatus(200);
    }
    
}
