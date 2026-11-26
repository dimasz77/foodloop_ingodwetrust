<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'organization', 'phone', 'avatar_path', 'lat', 'lng'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar_path ? asset('storage/'.$this->avatar_path) : null;
    }

    /**
     * Lokasi default pengguna untuk peta & form lapor (Jakarta bila belum diatur).
     *
     * @return array{lat: float, lng: float, saved: bool}
     */
    public function homeLocation(): array
    {
        $saved = $this->lat !== null && $this->lng !== null;

        return ['lat' => $saved ? $this->lat : -6.2088, 'lng' => $saved ? $this->lng : 106.8456, 'saved' => $saved];
    }

    /**
     * Tautan wa.me untuk menghubungi pengguna ini, atau null jika nomornya kosong.
     * Nomor lokal "08xx" diubah ke format internasional "628xx".
     */
    public function whatsappUrl(string $message = ''): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits.($message !== '' ? '?text='.rawurlencode($message) : '');
    }

    /** @var array<string, string> Peran pengguna beserta labelnya. */
    public const ROLES = [
        'provider' => 'Pemberi Makanan',
        'recipient' => 'Penerima Makanan',
        'processor' => 'Mitra Pengolah Organik',
    ];

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * Makanan layak konsumsi hanya untuk penerima (Human-First),
     * sisa organik hanya untuk mitra pengolah BSF/kompos (Earth-First).
     */
    public function canClaim(Report $report): bool
    {
        return $report->category === 'layak' ? $this->role === 'recipient' : $this->role === 'processor';
    }
}
