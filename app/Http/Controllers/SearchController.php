<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PanditProfile;
use App\Models\Service;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = PanditProfile::query()->approved()->with(['user', 'services']);

        // Filter by specific service if requested
        if ($request->filled('service')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('name', $request->service);
            });
        }

        // Apply Haversine formula for location-based search if lat/lng are provided
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $radius = 50; // default 50km radius

            // Haversine formula
            $query->selectRaw("*, 
                (6371 * acos(cos(radians(?)) * cos(radians(location_lat)) 
                * cos(radians(location_lng) - radians(?)) 
                + sin(radians(?)) * sin(radians(location_lat)))) AS distance", 
                [$lat, $lng, $lat]
            )
            ->having('distance', '<', $radius)
            ->orderBy('distance');
        }

        $pandits = $query->paginate(12);
        
        $services = Service::all();

        return view('search.index', compact('pandits', 'services'));
    }
}
