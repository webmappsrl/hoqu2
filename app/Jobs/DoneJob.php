<?php

namespace App\Jobs;

use App\Services\HoquJobService;
use App\Services\UserService;
use Wm\WmPackage\Http\HoquClient;
use Wm\WmPackage\Services\ProcessorClient;

/**
 * StoreJob class
 *
 * The store job that validate input and start the HokuJob pipeline
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
    public function handle(UserService $userService, ProcessorClient $processorClient)
    {
        //TODO: implement the CallerClient into wm-package
        //TODO: implement the DONE DONE request to caller
    }
}
