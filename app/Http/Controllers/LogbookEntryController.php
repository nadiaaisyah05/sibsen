<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogbookEntryController extends Controller
{
   public function dashboard(Request $request)
    {
    if ($request->user()->role === 'admin') {
        return redirect()->route('admin.absensi');
    }

    $entries = $request->user()->logbookEntries()->orderByDesc('tanggal')->get();
    $totalMenit = $entries->sum('durasi_menit');
    $recent = $entries->take(4);
    return view('dashboard', compact('totalMenit', 'recent', 'entries'));
    }

    public function index(Request $request)
    {
        $entries = $request->user()->logbookEntries()->orderByDesc('tanggal')->get();
        $totalMenit = $entries->sum('durasi_menit');
        return view('logbook.index', compact('entries', 'totalMenit'));
    }

    // Presensi masuk: validasi geofencing + catat jam masuk otomatis
    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'bukti' => ['nullable', 'image', 'max:2048'],
        ]);

        $jarak = LogbookEntry::jarakDariKawasan($request->latitude, $request->longitude);
        if ($jarak > config('kawasan.radius_meter')) {
            return back()->with('error', 'Anda berada di luar kawasan yang diizinkan (jarak ' . round($jarak) . ' m). Presensi ditolak.');
        }

        $data = [
            'user_id' => $request->user()->id,
            'tipe' => 'hadir',
            'status' => 'disetujui',
            'tanggal' => now()->toDateString(),
            'jam_mulai' => now()->toTimeString(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti-logbook', 'public');
        }

        $request->user()->logbookEntries()->create($data);

        return redirect()->route('logbook.index')->with('success', 'Presensi masuk berhasil dicatat pukul ' . now()->format('H:i') . '.');
    }

    // Presensi keluar: isi jam_selesai pada entri hari ini yang belum check-out
    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $jarak = LogbookEntry::jarakDariKawasan($request->latitude, $request->longitude);
        if ($jarak > config('kawasan.radius_meter')) {
            return back()->with('error', 'Anda berada di luar kawasan yang diizinkan. Presensi keluar ditolak.');
        }

        $entry = $request->user()->logbookEntries()
            ->whereDate('tanggal', now()->toDateString())
            ->where('tipe', 'hadir')
            ->whereNull('jam_selesai')
            ->latest()
            ->first();

        if (!$entry) {
            return back()->with('error', 'Tidak ditemukan presensi masuk hari ini yang belum check-out.');
        }

        $entry->update(['jam_selesai' => now()->toTimeString()]);

        return redirect()->route('logbook.index')->with('success', 'Presensi keluar berhasil dicatat pukul ' . now()->format('H:i') . '.');
    }

    // Form izin/sakit
    public function izin(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'tipe' => ['required', 'in:izin,sakit'],
            'deskripsi' => ['nullable', 'string'],
            'bukti' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'tipe' => $request->tipe,
            'status' => 'menunggu',
            'tanggal' => $request->tanggal,
            'judul' => ucfirst($request->tipe),
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('bukti-izin', 'public');
        }

        $request->user()->logbookEntries()->create($data);

        return redirect()->route('logbook.index')->with('success', 'Pengajuan ' . $request->tipe . ' berhasil dikirim.');
    }

    public function edit(LogbookEntry $logbook)
    {
        $this->authorizeOwner($logbook);
        return view('logbook.edit', ['entry' => $logbook]);
    }

    public function update(Request $request, LogbookEntry $logbook)
    {
        $this->authorizeOwner($logbook);
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);
        $logbook->update($data);
        return redirect()->route('logbook.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(LogbookEntry $logbook)
    {
        $this->authorizeOwner($logbook);
        if ($logbook->bukti) Storage::disk('public')->delete($logbook->bukti);
        $logbook->delete();
        return redirect()->route('logbook.index')->with('success', 'Data berhasil dihapus.');
    }

    private function authorizeOwner(LogbookEntry $logbook): void
    {
        abort_unless($logbook->user_id === auth()->id(), 403);
    }
}