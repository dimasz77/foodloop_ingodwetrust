<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ImpactCalculator as I;

class PageController extends Controller
{
    public function home()
    {
        Report::autoReroute();

        return view('home', [
            't' => I::totals(),
            'recent' => Report::latest('updated_at')->take(5)->get(),
            'urgent' => Report::where('category', 'layak')->where('status', 'waiting')->orderBy('expires_at')->take(3)->get(),
            'waiting' => Report::where('status', 'waiting')->count(),
        ]);
    }

    public function impact()
    {
        $cats = I::categories();
        $t = I::totals();

        return view('impact', [
            't' => $t, 'cats' => $cats, 'sum' => max(1, array_sum(array_column($cats, 1))),
            'board' => I::leaderboard(), 'monthly' => I::monthly(), 'eq' => I::equivalents($t['kg']),
            'pending' => (float) Report::where('status', '!=', 'delivered')->sum('weight_kg'),
        ]);
    }
}
