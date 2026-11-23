<?php
namespace App\Http\Controllers;

use App\Models\{Partner,Report};
use App\Services\ImpactCalculator as I;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function map()
    {
        Report::autoReroute();
        return view('map', [
            'pins' => Report::with('partner')->where('status', '!=', 'delivered')->get()->map->toPin()->values(),
            'partners' => Partner::all(['name', 'type', 'lat', 'lng']),
        ]);
    }

    public function create() { return view('report'); }

    public function store(Request $r)
    {
        $v = $r->validate([
            'provider_name' => 'required|string|max:80', 'title' => 'required|string|max:120', 'description' => 'nullable|string|max:500',
            'portions' => 'nullable|integer|min:1|max:5000', 'weight_kg' => 'nullable|numeric|min:0.1|max:5000',
            'expires_at' => 'required|date|after:now', 'lat' => 'required|numeric|between:-90,90', 'lng' => 'required|numeric|between:-180,180',
            'photo' => 'nullable|image|max:5120',
            'q1' => 'required|in:y,n', 'q2' => 'required|in:y,n', 'q3' => 'required|in:y,n', 'q4' => 'required|in:y,n',
        ]);
        if (!$r->filled('portions') && !$r->filled('weight_kg')) {
            return back()->withInput()->withErrors(['weight_kg' => 'Isi berat (kg) atau jumlah porsi.']);
        }
        $kg = $r->filled('weight_kg') ? (float) $v['weight_kg'] : I::kgFromPortions((int) $v['portions']);
        $por = $r->filled('portions') ? (int) $v['portions'] : I::portionsFromKg($kg);
        $cat = I::feasible($v) ? 'layak' : 'organik';
        $exp = \Carbon\Carbon::parse($v['expires_at']);
        $p = Partner::nearest($cat, (float) $v['lat'], (float) $v['lng']);

        $report = Report::create([
            'provider_name' => $v['provider_name'], 'title' => $v['title'], 'description' => $v['description'] ?? null,
            'category' => $cat, 'urgent' => $cat === 'layak' && $exp->lt(now()->addHours(2)),
            'portions' => $por, 'weight_kg' => $kg, 'expires_at' => $exp, 'lat' => $v['lat'], 'lng' => $v['lng'],
            'partner_id' => $p?->id, 'photo_path' => $r->file('photo')?->store('photos', 'public'),
        ]);
        session()->push('mine', $report->id);
        return redirect()->route('status')->with('ok', 'Laporan terkirim. Jalur: ' . ($cat === 'layak' ? 'Human-First' : 'Earth-First') . '.');
    }

    public function status(Request $r)
    {
        Report::autoReroute();
        $all = $r->boolean('all');
        $mine = session('mine', []) ?: [0];
        $scope = fn ($q) => $all ? $q : $q->whereIn('id', $mine);
        return view('status', [
            'all' => $all,
            'reports' => $scope(Report::with('partner')->where('status', '!=', 'delivered')->latest())->get(),
            'history' => $scope(Report::with('partner')->where('status', 'delivered')->latest('delivered_at'))->get(),
        ]);
    }

    public function act(Request $r, Report $report, string $action)
    {
        switch ($action) {
            case 'claim':
                if ($report->status === 'waiting') $report->update(['status' => 'claimed', 'volunteer_name' => 'Relawan Demo']);
                break;
            case 'start':
                if ($report->status === 'claimed') $report->update(['status' => 'on_the_way']);
                break;
            case 'complete':
                $r->validate(['proof' => 'required|image|max:5120'], ['proof.required' => 'Unggah foto bukti serah terima.']);
                if (in_array($report->status, ['claimed', 'on_the_way'])) {
                    $report->update(['status' => 'delivered', 'delivered_at' => now(), 'proof_path' => $r->file('proof')->store('proofs', 'public')]);
                }
                break;
            case 'reroute':
                if ($report->category === 'layak' && $report->status === 'waiting') $report->reroute();
                break;
        }
        return back()->with('ok', 'Status diperbarui.');
    }
}
