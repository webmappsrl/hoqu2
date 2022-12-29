<?php

namespace App\Jobs;

use App\Models\HoquJob;
use Illuminate\Bus\Queueable;
use App\Services\HoquJobService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Middleware\HoquSubJobMiddleware;
use Illuminate\Contracts\Queue\ShouldBeUnique;

/**
 * AbstractOwnedJob class
 *
 * An abstract class for all jobs owned by an HoquJob
 */
abstract class AbstractOwnedJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The service that handle jobs
   *
   * @var \App\Services\HoquJobService
   */
  protected $service;


  /**
   * The model to attach this job
   *
   * @var \App\Models\HoquJob
   */
  protected $hoqu_job;



  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(HoquJob $hoqu_job)
  {
    $this->hoqu_job = $hoqu_job;
    /**
     * @var \App\Services\HoquJobService
     */
    $this->service = app()->make(HoquJobService::class);
  }


  /**
   * Return a protected property
   *
   * @return \App\Models\HoquJob
   */
  public function getHoquJob()
  {
    return $this->hoqu_job;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  abstract public function handle();
}
