<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_successfully_log_in_via_admin_login_endpoint(): void
    {
        $this->artisan('admin:ensure')->assertSuccessful();

        $response = $this->post('/admin/login', [
            'email' => 'admin@vedmitra.in',
            'password' => 'Admin@12345',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => 'Password@123',
            'role' => 'user',
            'account_status' => 'active',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => 'Password@123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Access denied. Admin credentials required.']);
        $this->assertGuest();
    }

    public function test_invalid_password_fails_login(): void
    {
        $this->artisan('admin:ensure')->assertSuccessful();

        $response = $this->post('/admin/login', [
            'email' => 'admin@vedmitra.in',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors(['email' => 'Invalid credentials.']);
        $this->assertGuest();
    }
}
