<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoqu_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->index();
            $table->longText('input');
            $table->longText('output')->nullable();
            $table->foreignIdFor(User::class, 'caller_id');
            $table->foreignIdFor(User::class, 'processor_id')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoqu_jobs');
    }
};
