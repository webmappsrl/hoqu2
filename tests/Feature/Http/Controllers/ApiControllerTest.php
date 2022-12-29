<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiControllerTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->postJson('/api/store');

        $response->assertUnauthorized()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['message'])
            );
    }
}
