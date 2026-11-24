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

    public function create() { return view('report', ['partners' => Partner::all(['name', 'type', 'lat', 'lng'])]); }

    public function store(Request $r)
    {
        $v = $r->validate([
            'title' => 'required|string|max:120', 'provider_name' => 'required|string|max:80',
            'provider_type' => 'nullable|string|max:30', 'whatsapp' => 'nullable|string|max:20', 'allergen' => 'nullable|string|max:60',
            'delivery' => 'nullable|in:Diantar,Self-pickup', 'description' => 'nullable|string|max:500',
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
        $desc = trim(($v['description'] ?? '') . "\n" . implode(' · ', array_filter([
            $v['provider_type'] ?? null ? 'Jenis: ' . $v['provider_type'] : null, $v['whatsapp'] ?? null ? 'WA: ' . $v['whatsapp'] : null,
            $v['allergen'] ?? null ? 'Alergen/Diet: ' . $v['allergen'] : null, $v['delivery'] ?? null ? 'Pengantaran: ' . $v['delivery'] : null])));

        $report = Report::create([
            'provider_name' => $v['provider_name'], 'title' => $v['title'], 'description' => $desc ?: null,
            'category' => $cat, 'urgent' => $cat === 'layak' && $exp->lt(now()->addHours(2)),
            'portions' => $por, 'weight_kg' => $kg, 'expires_at' => $exp, 'lat' => $v['lat'], 'lng' => $v['lng'],
            'partner_id' => $p?->id, 'photo_path' => $r->file('photo')?->store('photos', 'public'),
        ]);
        session()->push('mine', $report->id);
        return redirect()->route('status', ['id' => $report->id])->with('ok', 'Laporan terkirim. Jalur: ' . ($cat === 'layak' ? 'Human-First' : 'Earth-First') . '.');
    }

    public function status(Request $r)
    {
        Report::autoReroute();
        $mine = session('mine', []);
        $scope = fn ($q) => $mine ? $q->whereIn('id', $mine) : $q; // belum punya laporan: tampilkan data demo
        $active = $scope(Report::with('partner')->where('status', '!=', 'delivered')->latest())->get();
        $done = $scope(Report::with('partner')->where('status', 'delivered')->latest('delivered_at'))->get();
        $cur = $r->filled('id') ? Report::with('partner')->find($r->id) : ($active->first() ?? $done->first());
        return view('status', ['cur' => $cur, 'others' => $active->merge($done)->take(8), 'demo' => !$mine]);
    }

    public function history()
    {
        $mine = session('mine', []);
        $q = Report::with('partner')->where('status', 'delivered')->latest('delivered_at');
        return view('history', ['items' => ($mine ? $q->whereIn('id', $mine) : $q)->get(), 'demo' => !$mine]);
    }

    public function act(Request $r, Report $report, string $action)
    {
        switch ($action) {
            case 'claim':
                if ($report->status === 'waiting') $report->update(['status' => 'claimed', 'volunteer_name' => 'Budiman Pekerti']);
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
