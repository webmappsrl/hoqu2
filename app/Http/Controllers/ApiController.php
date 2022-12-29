<?php

namespace App\Http\Controllers;

use App\Enums\HoquJobStatus;
use App\Models\HoquJob;
use Illuminate\Http\Request;
use App\Services\HoquJobService;

class ApiController extends Controller
{

    /**
     * Undocumented variable
     *
     * @var \App\Services\HoquJobService
     */
    protected $service;

    function __construct(HoquJobService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //must have: name, geometry

        //TODO validate

        $body = $request->json();
        $bodyStr = json_encode($body->all());



        // HOQU JOB CREATION
        $hoquJob = HoquJob::create([
            'status' => HoquJobStatus::New,
            'input' => $bodyStr,
            'caller_id' => $request->user()->id,
            'processor_id' => $this->service->getAvailableProcesserId($bodyStr)
        ]);

        $this->service->addStoreJob($hoquJob, $request->name, $request->geometry);

        return response(['message' => 'ok', 'created' => $hoquJob->id]);
    }
}
