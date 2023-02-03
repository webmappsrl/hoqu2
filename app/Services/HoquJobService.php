<?php

namespace App\Services;

use App\Models\User;
use App\Jobs\StoreJob;
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


  /**
   * This should return an User/instance id that can execute a job based on input
   *
   * @param string $input
   * @return \App\Models\User|false - User on success, false otherwise
   */
  public function getAvailableProcessorUser($job_name)
  {
    $r = User::whereJsonContains('hoqu_roles', 'processor')
      ->whereJsonContains('hoqu_processor_capabilities', $job_name)
      ->withCount('hoquJobs')
      ->get();



    return $r;
  }
}
