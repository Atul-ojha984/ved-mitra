<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateSubmission
{
    /**
     * Blocks rapid duplicate POST/PATCH/PUT/DELETE submissions with the same payload.
     */
    public function handle(Request $request, Closure $next, int $seconds = 12): Response
    {
        if (! $request->isMethodCacheable()) {
            $fingerprint = $this->fingerprint($request);
            $processingKey = "form-processing:$fingerprint";
            $completedKey = "form-completed:$fingerprint";

            if (Cache::has($completedKey) || ! Cache::add($processingKey, true, now()->addSeconds(15))) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'This form was already submitted. Please wait a moment before trying again.',
                    ], 429);
                }

                return back()
                    ->withErrors(['form' => 'This form was already submitted. Please wait a moment before trying again.'])
                    ->withInput($request->except(['password', 'password_confirmation', '_token']));
            }

            try {
                $response = $next($request);
                $hasValidationErrors = session()->has('errors');

                if (! $hasValidationErrors && $response->getStatusCode() < 400) {
                    Cache::put($completedKey, true, now()->addSeconds($seconds));
                }

                return $response;
            } finally {
                Cache::forget($processingKey);
            }
        }

        return $next($request);
    }

    private function fingerprint(Request $request): string
    {
        $payload = $request->except([
            '_method',
            '_token',
            'password',
            'password_confirmation',
            'razorpay_signature',
        ]);

        foreach ($request->files->all() as $key => $file) {
            if (is_array($file)) {
                continue;
            }

            $payload["file:$key"] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ];
        }

        ksort($payload);

        return sha1(json_encode([
            'session' => $request->session()->getId(),
            'user' => $request->user()?->id,
            'method' => $request->method(),
            'route' => $request->route()?->getName() ?? $request->path(),
            'payload' => $payload,
        ]));
    }
}
