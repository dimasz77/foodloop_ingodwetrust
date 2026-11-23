<?php
namespace App\Http\Controllers;

use App\Services\ImpactCalculator as I;

class PageController extends Controller
{
    public function home() { return view('home', ['t' => I::totals()]); }

    public function impact()
    {
        $cats = I::categories();
        return view('impact', ['t' => I::totals(), 'cats' => $cats, 'sum' => max(1, array_sum(array_column($cats, 1))), 'board' => I::leaderboard()]);
    }
}
