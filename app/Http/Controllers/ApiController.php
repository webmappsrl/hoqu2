<?php

namespace App\Http\Controllers;

use App\Enums\HoquJobStatus;
use App\Models\HoquJob;
use Illuminate\Http\Request;
use App\Services\HoquJobService;

/**
 * ApiController class
 *
 * The main api routes controller
 */
class ApiController extends Controller
{

    /**
     * The service to handle jobs
     *
     * @var \App\Services\HoquJobService
     */
    protected $service;

    /**
     * The constructor method
     *
     * @param HoquJobService $service
     */
    function __construct(HoquJobService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a new HoquJob with a LaravelJob.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //must have: name, input

        //TODO: validate?

        $body = $request->json();
        $inputAsString = json_encode($body->get('input'));


        // HOQU JOB CREATION
        $hoquJob = HoquJob::create([
            'status' => HoquJobStatus::New,
            'input' => $inputAsString,
            'name' => $body->get('name'),
            'caller_id' => $request->user()->id
        ]);

        // LARAVEL JOB CREATION
        $this->service->addStoreJob($hoquJob, $body->get('name'), $body->get('input'));

        return response(['message' => 'created', 'job_id' => $hoquJob->id]);
    }
}
