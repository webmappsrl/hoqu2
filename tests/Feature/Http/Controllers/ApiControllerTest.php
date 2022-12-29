<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\HoquJob;
use Tests\TestCase;
use App\Models\User;
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
    public function test_store_route_existence()
    {
        $response = $this->postJson('/api/store');

        $response->assertUnauthorized()
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['message'])
            );
    }

    public function test_store_jobs_creation()
    {

        $user = User::first();
        $data = ['name' => 'AddLocationsToPoint', 'input' => [
            "type" => "Point",
            "coordinates" => [1, 2]
        ]];
        $response = $this->actingAs($user)->postJson('/api/store', $data);

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json->hasAll(['message', 'job_id'])
            );
    }
}
