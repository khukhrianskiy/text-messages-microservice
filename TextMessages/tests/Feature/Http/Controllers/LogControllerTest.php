<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class LogControllerTest extends TestCase
{
    public function testIndex(): void
    {
        $response = $this->get(route('log.index'));

        $response->assertStatus(200);
    }
}
