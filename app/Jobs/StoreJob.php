<?php

namespace App\Jobs;

class StoreJob extends AbstractOwnedJob
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hoqu_job, $job_name, $geometry)
    {
        parent::__construct($hoqu_job);

        $this->job_name = $job_name;
        $this->geometry = $geometry;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(60);
    }
}
