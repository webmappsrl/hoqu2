<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\PostgreSql;
use Spatie\DbDumper\Exceptions\CannotStartDump;
use Spatie\DbDumper\Exceptions\DumpFailed;

class CreateInstanceUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hoqu:create-user';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with master token (that can create user on remote) with some data';


    /**
     * Undocumented variable
     *
     * @var \App\Services\UserService
     */
    protected $userService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ip = $this->ask('Please insert the ip of remote/local instance');
        $role = $this->anticipate('Please insert the role of this user [processor|caller]', ['processor', 'caller']);
        $token = $this->secret('Please insert the token with i will create a new user on remote instance via /api/register API');
        $endpoint = $this->ask('Please insert the endpoint of remote/local instance');

        $tokenSecret = substr($token, 0, 3) . '*******' . substr($token, -3);

        $this->info('Your choices:');

        $checkStr = <<<EOT
    Ip: $ip
    Role: $role
    Token: $tokenSecret
    Endpoint: $endpoint
EOT;

        $this->line($checkStr);

        if ($this->confirm('Do you wish to continue?', true)) {

            $arr = $this->userService->createInstanceUser($ip, $role, $token, $endpoint);

            /**
             * @var \App\Models\User
             */
            $user = $arr['user'];

            /**
             * @var string
             */
            $token = $arr['token'];


            $checkStr = <<<EOT
    Id: {$user->id}
    Email: {$user->email}
    Name: {$user->name}
    Token: $token
EOT;
            $this->info("Success! Here the details of new user:");
            $this->line($checkStr);
            $this->info("Remember to save the token on remote instance");
        }
    }

    protected function log($message, $type = 'info')
    {
        Log::$type($message);
        $this->$type($message);
    }
}
