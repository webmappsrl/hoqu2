<?php

namespace App\Services;

use App\Models\User;
use App\Jobs\StoreJob;
use App\Jobs\DoneJob;
use App\Models\HoquJob;
use App\Models\LaravelJob;

/**
 * Services about HoquJob and its jobs
 */
class HoquJobService
{
  /**
   * Create a new Job of type STORE
   *
   * @param HoquJob $hoquJob
   * @param string $name
   * @param [type] $input
   * @return void
   */
  public function addStoreJob(HoquJob $hoquJob, string $name, $input)
  {
    StoreJob::dispatch($hoquJob, $name, $input)->onQueue('store');
  }

  /**
   * Create a new Job of type DONE
   *
   * @param HoquJob $hoquJob
   * @param string $output
   * @return void
   */
  public function addDoneJob(HoquJob $hoquJob, string $output)
  {
    DoneJob::dispatch($hoquJob, $output)->onQueue('done');
  }

  /**
   * Attach a LaravelJob model to HoquJob model 1:N relation
   *
   * @param LaravelJob $job
   * @param HoquJob $hoquJob
   * @return void
   */
  public function addJobToHoquJob(LaravelJob $job, HoquJob $hoquJob)
  {
    $hoquJob->laravelJobs()->save($job);
  }
}
