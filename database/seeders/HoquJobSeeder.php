<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HoquJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Wm\WmPackage\Enums\JobStatus;

class HoquJobSeeder extends Seeder
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


        //for each caller user create 100 random status hoqujobs
        $callerUsers->each(function ($user) {
            HoquJob::factory(100)->create(
                [
                    'status' => collect(JobStatus::cases())->random(),
                    'caller_id' => $user->id
                ]
            );
        });

        //for each processor user create 100 random status hoqujobs
        $processorUsers->each(function ($user) {
            HoquJob::factory(100)->create([
                'status' => collect(JobStatus::cases())->random(),
                'processor_id' => $user->id
            ]);
        });
    }
}
