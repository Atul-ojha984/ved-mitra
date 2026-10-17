<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDiagnosticTest extends TestCase
{
    use RefreshDatabase;

    public function test_diagnostic_endpoint_denies_access_when_token_env_is_not_configured(): void
    {
        putenv('ADMIN_DIAGNOSTIC_TOKEN=');
        $_ENV['ADMIN_DIAGNOSTIC_TOKEN'] = '';

        $response = $this->getJson('/diagnostic/admin', [
            'X-Diagnostic-Token' => 'some-random-token',
        ]);

        $response->assertStatus(403);
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', (string) $response->headers->get('Cache-Control'));
    }

    public function test_diagnostic_endpoint_denies_access_with_invalid_token(): void
    {
        putenv('ADMIN_DIAGNOSTIC_TOKEN=SuperSecretDiagnosticToken123456789!');
        $_ENV['ADMIN_DIAGNOSTIC_TOKEN'] = 'SuperSecretDiagnosticToken123456789!';

        $response = $this->getJson('/diagnostic/admin', [
            'X-Diagnostic-Token' => 'wrong-token',
        ]);

        $response->assertStatus(401);
    }

    public function test_diagnostic_endpoint_returns_expected_fields_when_admin_exists(): void
    {
        $token = 'SuperSecretDiagnosticToken123456789!';
        putenv("ADMIN_DIAGNOSTIC_TOKEN={$token}");
        $_ENV['ADMIN_DIAGNOSTIC_TOKEN'] = $token;

        User::create([
            'name' => 'Admin',
            'email' => 'admin@vedmitra.in',
            'password' => 'Admin@12345',
            'role' => 'admin',
            'account_status' => 'active',
        ]);

        $response = $this->getJson('/diagnostic/admin', [
            'X-Diagnostic-Token' => $token,
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('must-revalidate', (string) $response->headers->get('Cache-Control'));
        $response->assertHeader('Pragma', 'no-cache');
        $response->assertHeader('Expires', '0');

        $response->assertExactJson([
            'admin_found' => true,
            'password_matches' => true,
            'role_is_admin' => true,
            'database_connection_driver' => 'sqlite',
        ]);
    }

    public function test_diagnostic_endpoint_supports_bearer_token(): void
    {
        $token = 'SuperSecretDiagnosticToken123456789!';
        putenv("ADMIN_DIAGNOSTIC_TOKEN={$token}");
        $_ENV['ADMIN_DIAGNOSTIC_TOKEN'] = $token;

        $response = $this->withToken($token)->getJson('/diagnostic/admin');

        $response->assertStatus(200);
        $this->assertArrayHasKey('admin_found', $response->json());
    }

    public function test_diagnostic_endpoint_reports_when_admin_is_missing(): void
    {
        $token = 'SuperSecretDiagnosticToken123456789!';
        putenv("ADMIN_DIAGNOSTIC_TOKEN={$token}");
        $_ENV['ADMIN_DIAGNOSTIC_TOKEN'] = $token;

        $response = $this->getJson('/diagnostic/admin', [
            'X-Diagnostic-Token' => $token,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'admin_found' => false,
            'password_matches' => false,
            'role_is_admin' => false,
            'database_connection_driver' => 'sqlite',
        ]);
    }
}
