<?php

namespace App\Jobs;

use App\Services\UserService;
use Wm\WmPackage\Enums\JobStatus;
use Wm\WmPackage\Facades\CallerClient;

/**
 * Done class
 *
 * The done job, when processor calls hoqu with job output
 */
class DoneJob extends AbstractOwnedJob
{

    protected $output;

    /**
     * Create a new store job instance.
     *
     * @return void
     */
    public function __construct($hoqu_job, $output)
    {
        parent::__construct($hoqu_job);

        $this->output = $output;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService)
    {

        $this->getHoquJob()->output = $this->output;

        // Exec the done done call to caller
        $response = CallerClient::done($this->getHoquJob()->caller, [
            'hoqu_job_id' => $this->getHoquJob()->id,
            'output' => $this->output
        ]);

        if ($response->ok()) {
            //set status and save
            $this->getHoquJob()->setStatusAndSave(JobStatus::Done);
        } else {
            //TODO: log something please
            //set status and save
            $this->getHoquJob()->setStatusAndSave(JobStatus::Error);
        }
    }
}
