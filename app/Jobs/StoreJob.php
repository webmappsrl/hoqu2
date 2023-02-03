<?php

namespace App\Jobs;

use App\Services\HoquJobService;
use App\Services\UserService;

/**
 * StoreJob class
 *
 * The store job that validate input and start the HokuJob pipeline
 */
class StoreJob extends AbstractOwnedJob
{

    protected $job_name;
    protected $input;

    /**
     * Create a new store job instance.
     *
     * @return void
     */
    public function __construct($hoqu_job, $job_name, $input)
    {
        parent::__construct($hoqu_job);

        $this->job_name = $job_name;

        //TODO: VALIDATE INPUT STRING
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService)
    {
        // TODO: implement step PROCESS (HOQU->PROCESSOR). trova il server opportuno (libero e capace) e chiamalo
        $availableProcessor = $userService->getAvailableProcessorUser($this->job_name);

        // TODO: get endpoint
        $availableProcessor->endpoint
    }
}
