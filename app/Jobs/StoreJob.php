<?php

namespace App\Jobs;

use App\Services\HoquJobService;
use App\Services\UserService;
use Wm\WmPackage\Http\HoquClient;
use Wm\WmPackage\Services\ProcessorClient;

/**
 * StoreJob class
 *
 * The store job that validate input and start the HokuJob pipeline
 */
class StoreJob extends AbstractOwnedJob
{

    protected $job_name;
    protected $input;

    /**
     * Create a new store job instance.
     *
     * @return void
     */
    public function __construct($hoqu_job, $job_name, $input)
    {
        parent::__construct($hoqu_job);

        $this->job_name = $job_name;

        //TODO: VALIDATE INPUT STRING
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserService $userService, ProcessorClient $processorClient)
    {
        //TODO: getAvailableProcessorUser lancia un eccezione se non è riuscito a trovare l'utente,
        // gestire questa casistica (chiamo il caller e gli dico failed?)

        // Trova il server opportuno (libero e capace)
        $availableProcessor = $userService->getAvailableProcessorUser($this->job_name);

        // Chiamalo per eseguire il job
        $response = $processorClient->do($availableProcessor, [
            'name' => $this->job_name,
            'input' => $this->input
        ]);

        if ($response->ok()) {
            //TODO: $this->info("Job succesfully created on remote processor. User : {$availableProcessor->name}(ID: $availableProcessor->id)");
        } else {
            //TODO: $this->error("Something went wrong sending job to processor");
            dump($response->json());
        }
    }
}
