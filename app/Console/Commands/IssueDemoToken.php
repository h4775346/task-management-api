<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class IssueDemoToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:demo-token {email : The email of the user to generate token for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a JWT token for a demo user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found.");

            return 1;
        }

        $token = app('tymon.jwt.auth')->fromUser($user);

        $this->info("Access token for {$user->email}:");
        $this->line($token);

        return 0;
    }
}
