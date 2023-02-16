<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HoquJob;
use App\Models\LaravelJob;
use Illuminate\Database\Seeder;
use Wm\WmPackage\Enums\JobStatus;

class LaravelJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve caller users
        $callerUsers = User::whereJsonContains('hoqu_roles', 'caller')->get();

        //Retrieve processor users
        $processorUsers = User::whereJsonContains('hoqu_roles', 'processor')->get();


        //for each caller user create 100 random status laraveljobs
        $callerUsers->each(function ($user) {
            LaravelJob::factory(100)->create(
                [
                    'queue' => 'default',
                    'payload' => '',
                    'attempts' => 0,
                    'status' => collect(JobStatus::cases())->random(),
                    'hoqu_job_id' => HoquJob::where('caller_id', $user->id)->inRandomOrder()->first()->id
                ]
            );
        });

        //for each processor user create 100 random status laraveljobs
        $processorUsers->each(function ($user) {
            LaravelJob::factory(100)->create([
                'queue' => 'default',
                'payload' => '',
                'attempts' => 0,
                'status' => collect(JobStatus::cases())->random(),
                'hoqu_job_id' => HoquJob::where('processor_id', $user->id)->inRandomOrder()->first()->id
            ]);
        });
    }
}
