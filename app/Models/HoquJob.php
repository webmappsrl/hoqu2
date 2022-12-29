<?php

namespace App\Models;

use App\Services\HoquJobService;
use App\Enums\HoquJobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * HoquJob class
 *
 * A simple laravel jobs container
 */
class HoquJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'input',
        'output',
        'caller_id',
        'processor_id',
        'name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //only some statuses are allowed
        'status' => HoquJobStatus::class,
    ];

    /**
     * The service that handle jobs
     *
     * @var \App\Services\HoquJobService
     */
    protected $hoquJobService;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->hoquJobService = app()->make(HoquJobService::class);
    }

    /**
     * The user/instace who asked for this job
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }


    /**
     * The user/instace that have to process this job (nullable)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processor_id');
    }


    /**
     * The laravel jobs associated to this HoquJob, wraped inside a model
     * (works because we use database driver for queues)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function laravelJobs()
    {
        return $this->hasMany(LaravelJob::class);
    }
}
