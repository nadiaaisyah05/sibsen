<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LogbookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tipe', 'status', 'tanggal', 'jam_mulai', 'jam_selesai',
        'judul', 'deskripsi', 'bukti', 'latitude', 'longitude',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurasiMenitAttribute(): int
    {
        if (!$this->jam_mulai || !$this->jam_selesai) return 0;
        $mulai = Carbon::parse($this->jam_mulai);
        $selesai = Carbon::parse($this->jam_selesai);
        if ($selesai->lessThan($mulai)) $selesai->addDay();
        return $mulai->diffInMinutes($selesai);
    }

    public function getDurasiLabelAttribute(): string
    {
        $menit = $this->durasi_menit;
        return floor($menit / 60) . 'j ' . ($menit % 60) . 'm';
    }

    // Haversine formula: hitung jarak (meter) dari titik kawasan
    public static function jarakDariKawasan(float $lat, float $lng): float
    {
        $kawasanLat = config('kawasan.latitude');
        $kawasanLng = config('kawasan.longitude');

        $earthRadius = 6371000;
        $dLat = deg2rad($lat - $kawasanLat);
        $dLng = deg2rad($lng - $kawasanLng);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($kawasanLat)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}