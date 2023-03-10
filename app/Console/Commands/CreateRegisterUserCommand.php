<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateRegisterUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hoqu:create-register-user
    {--password=false : the password to use to register user}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user that have the ability to register new users via API with token (or update the existing one)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(UserService $userService)
    {

        if ($this->option('password') !== 'false') {
            $password = $this->option('password');
        } else {
            $password = $this->ask('Please insert the password of register user');
        }

        $user = $userService->createRegisterUser($password);

        $checkStr = <<<EOT
HOQU_REGISTER_USERNAME={$user->email}
HOQU_REGISTER_PASSWORD=$password
EOT;

        $this->info("Success! Now you can copy these lines in .env file on your caller/processor instance, then launch `php artisan config:clear` and `php artisan hoqu:register-user` to establish connection between hoqu and caller/processor");
        $this->newLine();
        $this->line($checkStr);
        $this->newLine();

        return Command::SUCCESS;
    }

    protected function log($message, $type = 'info')
    {
        Log::$type($message);
        $this->$type($message);
    }
}
