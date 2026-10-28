<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $nama = $request->input('nama');
        $tipe = $request->input('tipe');

        $query = LogbookEntry::with('user')
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2));

        if ($nama) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$nama}%"));
        }
        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        $entries = $query->orderByDesc('tanggal')->get();

        $stats = [
            'hadir'    => $entries->where('tipe', 'hadir')->count(),
            'izin'     => $entries->where('tipe', 'izin')->count(),
            'sakit'    => $entries->where('tipe', 'sakit')->count(),
            'menunggu' => $entries->where('status', 'menunggu')->count(),
        ];

        return view('admin.absensi', compact('entries', 'bulan', 'nama', 'tipe', 'stats'));
    }

    // Approve / tolak pengajuan izin-sakit
    public function validasi(Request $request, LogbookEntry $logbook)
    {
        $request->validate(['status' => ['required', 'in:disetujui,ditolak']]);
        $logbook->update(['status' => $request->status]);
        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function edit(LogbookEntry $logbook)
    {
        return view('admin.absensi-edit', ['entry' => $logbook]);
    }

    public function update(Request $request, LogbookEntry $logbook)
    {
        $data = $request->validate([
            'tanggal'     => ['required', 'date'],
            'jam_mulai'   => ['nullable'],
            'jam_selesai' => ['nullable'],
            'judul'       => ['required', 'string', 'max:255'],
            'deskripsi'   => ['nullable', 'string'],
            'status'      => ['required', 'in:menunggu,disetujui,ditolak'],
        ]);

        $logbook->update($data);

        return redirect()->route('admin.absensi')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(LogbookEntry $logbook)
    {
        if ($logbook->bukti) {
            Storage::disk('public')->delete($logbook->bukti);
        }
        $logbook->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    // Export ke CSV (bisa dibuka Excel)
    public function export(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $entries = LogbookEntry::with('user')
            ->whereYear('tanggal', substr($bulan, 0, 4))
            ->whereMonth('tanggal', substr($bulan, 5, 2))
            ->orderBy('tanggal')
            ->get();

        $filename = "absensi-{$bulan}.csv";
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($entries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama', 'NIM', 'Tanggal', 'Tipe', 'Status', 'Jam Masuk', 'Jam Keluar', 'Judul', 'Deskripsi']);
            foreach ($entries as $e) {
                fputcsv($file, [
                    $e->user->name,
                    $e->user->nim,
                    $e->tanggal->format('d/m/Y'),
                    $e->tipe,
                    $e->status,
                    $e->jam_mulai,
                    $e->jam_selesai,
                    $e->judul,
                    $e->deskripsi,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}