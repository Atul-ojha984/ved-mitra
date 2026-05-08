<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LegalController extends Controller
{
    public function show(string $slug)
    {
        $pages = config('legal');

        abort_unless(isset($pages[$slug]), 404);

        return view('legal.show', [
            'slug' => $slug,
            'page' => $pages[$slug],
        ]);
    }

    public function sitemap(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $staticRoutes = [
            url('/'),
            route('pandit.search'),
            route('kundli.form'),
            route('ebooks.index'),
            route('festivals.index'),
            route('rashi.index'),
        ];

        foreach (array_keys(config('legal')) as $slug) {
            $staticRoutes[] = route('legal.show', $slug);
        }

        $urls = collect($staticRoutes)
            ->map(fn (string $url) => str_replace($base, config('app.url'), $url))
            ->unique()
            ->values();

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
