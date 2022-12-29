<?php

namespace App\Jobs;

class StoreJob extends AbstractOwnedJob
{
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
    public function handle()
    {
        // TODO: implement step PROCESS (HOQU->PROCESSOR). trova il server opportuno (libero e capace) e chiamalo
    }
}
