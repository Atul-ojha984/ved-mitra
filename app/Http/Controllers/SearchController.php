<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchPanditsRequest;
use App\Models\PanditProfile;
use App\Models\Service;

class SearchController extends Controller
{
    public function index(SearchPanditsRequest $request)
    {
        $filters = $request->validated();
        $query = PanditProfile::query()->approved()->with(['user', 'services']);

        // Filter by specific service if requested
        if (!empty($filters['service'])) {
            $query->whereHas('services', function ($q) use ($filters) {
                $q->where('name', $filters['service']);
            });
        }

        // Apply Haversine formula for location-based search if lat/lng are provided
        if (isset($filters['lat'], $filters['lng'])) {
            $lat = (float) $filters['lat'];
            $lng = (float) $filters['lng'];
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
