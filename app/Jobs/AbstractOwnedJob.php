<?php

namespace App\Jobs;

use App\Models\HoquJob;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * AbstractOwnedJob class
 *
 * An abstract class for all jobs owned by an HoquJob
 */
abstract class AbstractOwnedJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
