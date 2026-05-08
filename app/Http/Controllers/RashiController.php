<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rashi;

class RashiController extends Controller
{
    public function index()
    {
        $rashis = Rashi::all();
        return view('rashi.index', compact('rashis'));
    }

    public function show(Rashi $rashi)
    {
        return view('rashi.show', compact('rashi'));
    }
}
