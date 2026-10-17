<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnsureAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:ensure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Idempotently create or update the dedicated admin user without affecting other data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@vedmitra.in');
        $password = (string) env('ADMIN_PASSWORD', 'Admin@12345');
        $name = (string) env('ADMIN_NAME', 'Admin');

        $this->info("Ensuring admin user account for: {$email}");

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->name = $name;
            $user->password = $password; // Cast 'hashed' in User model will hash properly
            $user->role = 'admin';
            $user->account_status = 'active';
            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }
            $user->save();

            $this->info("Admin user [{$email}] was successfully updated.");
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password, // Cast 'hashed' in User model will hash properly
                'role' => 'admin',
                'account_status' => 'active',
                'email_verified_at' => now(),
            ]);

            $this->info("Admin user [{$email}] was successfully created.");
        }

        return Command::SUCCESS;
    }
}
