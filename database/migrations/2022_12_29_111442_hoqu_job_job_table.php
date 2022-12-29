<?php

use App\Models\HoquJob;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoqu_job_job', function (Blueprint $table) {
            $table->foreignIdFor(HoquJob::class, 'hoqu_job_id');
            $table->foreignId('job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoqu_job_job');
    }
};
