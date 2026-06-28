<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Sample;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $totalUji = Sample::count();
        $layakKirim = Sample::where('status', 'Layak Kirim')->count();
        $tidakLayak = Sample::where('status', 'Tidak Layak')->count();
        
        $latestSamples = Sample::with('material')
            ->latest()
            ->take(5)
            ->get();

        $materials = Material::all();

        return view('dashboard.index', compact(
            'totalUji', 
            'layakKirim', 
            'tidakLayak', 
            'latestSamples', 
            'materials'
        ));
    }
}
