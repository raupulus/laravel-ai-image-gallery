<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:token {email? : Admin user email address} {--name=general : Token name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a scoped Sanctum API token (collections:write) for an admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        $user = $email
            ? User::query()->where('email', $email)->first()
            : User::query()->whereHas('role', fn ($query) => $query->where('slug', 'admin'))->first();

        if (! $user) {
            $this->error('No matching user found.');

            return self::FAILURE;
        }

        if (! $user->isAdmin) {
            $this->error('API write tokens can only be issued to admin users.');

            return self::FAILURE;
        }

        $tokenName = (string) $this->option('name');
        $token = $user->createToken($tokenName, ['collections:write']);

        $this->line($token->plainTextToken);

        return self::SUCCESS;
    }
}
