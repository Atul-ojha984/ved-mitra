<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Idempotently create or update the dedicated admin user.
     */
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@vedmitra.in');
        $password = (string) env('ADMIN_PASSWORD', 'Admin@12345');
        $name = (string) env('ADMIN_NAME', 'Admin');

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
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password, // Cast 'hashed' in User model will hash properly
                'role' => 'admin',
                'account_status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
    }
}
