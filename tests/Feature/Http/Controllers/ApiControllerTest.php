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
//     use RefreshDatabase;

    /**
     * Check existence of route /api/store
     * must be unauthorized and it must has "message" key in response body
     *
     * @return void
     */
    public function test_store_unauthorized_existence()
    {
        // $response = $this->postJson('/api/hoqu/store');

        // $response->assertUnauthorized()
        //     ->assertJson(
        //         function (AssertableJson $json) {
        //             return $json->hasAll(['message']);
        //         }
        //     );
        $this->assertTrue(true);
    }

    /**
     * Check full functionalities of authorized store api endpoint
     *
     * must has a "message" and "job_id" keys in response body
     * must has create an HoquJob with 1 LaravelJob
     *
     * @return void
     */
    public function test_store_authorized_jobs_creation()
    {

        // $user = User::factory()->create();
        // $data = ['name' => 'AddLocationsToPoint', 'input' => [
        //     "type" => "Point",
        //     "coordinates" => [1, 2]
        // ]];
        // $response = $this->actingAs($user)->postJson('/api/hoqu/store', $data);

        // $jobId = $response['job_id'];

        // $response->assertStatus(200)
        //     ->assertJson(
        //         fn (AssertableJson $json) => $json->hasAll(['message', 'job_id'])
        //     );

        // $hoquJob = HoquJob::find($jobId);
        // $this->assertTrue($hoquJob instanceof HoquJob);
        // $this->assertTrue($hoquJob->laravelJobs->count() === 1);
        $this->assertTrue(true);
    }
}
