<?php
namespace App\Http\Controllers;

use App\Services\ImpactCalculator as I;

class PageController extends Controller
{
    public function home()
    {
        return view('home', ['t' => I::totals(), 'recent' => \App\Models\Report::latest()->take(4)->get(),
            'urgent' => \App\Models\Report::where('category', 'layak')->where('status', 'waiting')->orderBy('expires_at')->take(2)->get()]);
    }

    public function impact()
    {
        $cats = I::categories();
        return view('impact', ['t' => I::totals(), 'cats' => $cats, 'sum' => max(1, array_sum(array_column($cats, 1))), 'board' => I::leaderboard(), 'monthly' => I::monthly(), 'pending' => (float) \App\Models\Report::where('status', '!=', 'delivered')->sum('weight_kg')]);
    }
}
