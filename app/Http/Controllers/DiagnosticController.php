<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DiagnosticController extends Controller
{
    /**
     * Temporary diagnostic endpoint to verify admin user status in the active database.
     * Strictly protected by ADMIN_DIAGNOSTIC_TOKEN.
     */
    public function checkAdmin(Request $request): JsonResponse
    {
        $noCacheHeaders = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $expectedToken = (string) env('ADMIN_DIAGNOSTIC_TOKEN');

        // Strictly reject if token is not configured or too short (prevent default/empty bypass)
        if ($expectedToken === '' || strlen($expectedToken) < 16) {
            return response()->json([
                'error' => 'Diagnostic endpoint is not enabled or token is not configured.',
            ], 403, $noCacheHeaders);
        }

        // Retrieve token from request header (support X-Diagnostic-Token and Bearer)
        $providedToken = (string) ($request->header('X-Diagnostic-Token') ?? $request->bearerToken() ?? '');

        if ($providedToken === '' || ! hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'error' => 'Unauthorized access.',
            ], 401, $noCacheHeaders);
        }

        // Expected admin credentials (from environment or defaults)
        $expectedEmail = (string) env('ADMIN_EMAIL', 'admin@vedmitra.in');
        $expectedPassword = (string) env('ADMIN_PASSWORD', 'Admin@12345');

        $admin = User::where('email', $expectedEmail)->first();

        $adminFound = $admin !== null;
        $passwordMatches = $adminFound && Hash::check($expectedPassword, $admin->password);
        $roleIsAdmin = $adminFound && $admin->role === 'admin';

        try {
            $driver = DB::connection()->getDriverName();
        } catch (\Throwable) {
            $driver = (string) config('database.default', 'unknown');
        }

        // Return ONLY the four strictly required fields. Never expose credentials, hash, or other data.
        return response()->json([
            'admin_found' => (bool) $adminFound,
            'password_matches' => (bool) $passwordMatches,
            'role_is_admin' => (bool) $roleIsAdmin,
            'database_connection_driver' => (string) $driver,
        ], 200, $noCacheHeaders);
    }
}
