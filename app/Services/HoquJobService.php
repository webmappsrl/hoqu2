<?php

namespace App\Services;

use App\Models\LaravelJob;
use App\Jobs\StoreJob;
use App\Models\HoquJob;

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
   * @param [type] $geometry
   * @return void
   */
  public function addStoreJob(HoquJob $hoquJob, string $name, $geometry)
  {
    StoreJob::dispatch($hoquJob, $name, $geometry)->onQueue('store');
  }

  /**
   * Attach a LaravelJob model to HoquJob model many to many relation
   *
   * @param LaravelJob $job
   * @param HoquJob $hoquJob
   * @return void
   */
  public function addJobToHoquJob(LaravelJob $job, HoquJob $hoquJob)
  {
    $hoquJob->jobs()->attach($job);
  }


  public function getAvailableProcesserId($input): int
  {
    //must return a valid User id
    //TODO
    return 1;
  }
}
