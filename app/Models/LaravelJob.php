<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaravelJob extends Model
{
  protected $table = 'jobs';
  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;


  /**
   * The owner HokuJob (nullable)
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function hokuJob()
  {
    return $this->belongsTo(HoquJob::class, 'hoqu_job_id');
  }
}
