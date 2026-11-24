<?php
namespace App\Models;

use App\Services\ImpactCalculator;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];
    protected $casts = [
        'expires_at' => 'datetime', 'delivered_at' => 'datetime', 'urgent' => 'boolean', 'rerouted' => 'boolean',
        'weight_kg' => 'float', 'lat' => 'float', 'lng' => 'float',
    ];
    public const STATUS = ['waiting' => 'Menunggu Klaim', 'claimed' => 'Diklaim', 'on_the_way' => 'Dalam Perjalanan', 'delivered' => 'Tersalurkan'];

    public function partner() { return $this->belongsTo(Partner::class); }
    public function getStatusLabelAttribute(): string { return self::STATUS[$this->status] ?? $this->status; }
    public function getStepAttribute(): int { return (int) array_search($this->status, array_keys(self::STATUS)); }
    public function getCo2eAttribute(): float { return ImpactCalculator::co2e($this->weight_kg); }
    public function getUrgentNowAttribute(): bool
    {
        return $this->category === 'layak' && $this->status !== 'delivered' && $this->expires_at->lt(now()->addHour());
    }
    public function getColorAttribute(): string
    {
        return $this->category === 'organik' ? '#f59e0b' : ($this->urgent_now ? '#dc2626' : '#047857');
    }

    /** Auto re-route: laporan layak yang tak diklaim sampai kedaluwarsa dialihkan ke jalur organik. */
    public static function autoReroute(): int
    {
        $n = 0;
        foreach (self::where('category', 'layak')->where('status', 'waiting')->where('expires_at', '<', now())->get() as $r) {
            $r->reroute(); $n++;
        }
        return $n;
    }

    public function reroute(): void
    {
        $p = Partner::nearest('organik', $this->lat, $this->lng);
        $this->update(['category' => 'organik', 'rerouted' => true, 'urgent' => false, 'partner_id' => $p?->id, 'expires_at' => now()->addHours(6)]);
    }

    public function toPin(): array
    {
        return [
            'id' => $this->id, 'title' => $this->title, 'provider' => $this->provider_name, 'category' => $this->category,
            'urgent' => $this->urgent_now, 'color' => $this->color, 'portions' => $this->portions, 'kg' => $this->weight_kg,
            'lat' => $this->lat, 'lng' => $this->lng, 'expires' => $this->expires_at->toIso8601String(),
            'status' => $this->status, 'label' => $this->status_label, 'rerouted' => $this->rerouted,
            'partner' => $this->partner?->name, 'co2e' => $this->co2e,
        ];
    }
}
