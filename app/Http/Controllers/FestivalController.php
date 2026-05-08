<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Festival;
use Carbon\Carbon;

class FestivalController extends Controller
{
    public function index(Request $request)
    {
        $query = Festival::orderBy('festival_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('month')) {
            $query->whereMonth('festival_date', $request->month);
        }

        $upcoming = Festival::where('festival_date', '>=', today())->orderBy('festival_date')->take(5)->get();
        $festivals = $query->paginate(20)->appends($request->query());
        return view('festivals.index', compact('festivals', 'upcoming'));
    }

    public function show(Festival $festival)
    {
        $nearby = Festival::where('id', '!=', $festival->id)
            ->whereBetween('festival_date', [$festival->festival_date->copy()->subDays(15), $festival->festival_date->copy()->addDays(15)])
            ->take(4)->get();
        return view('festivals.show', compact('festival', 'nearby'));
    }
}
