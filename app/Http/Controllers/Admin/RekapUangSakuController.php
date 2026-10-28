<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogbookEntry;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapUangSakuController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $data = $this->hitungRekap($bulan);

        return view('admin.uang-saku', array_merge($data, ['bulan' => $bulan]));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'uang_saku_bulanan' => ['required', 'numeric', 'min:0'],
            'potongan_per_alpha' => ['required', 'numeric', 'min:0'],
            'potongan_per_setengah_hari' => ['required', 'numeric', 'min:0'],
            'jam_minimal_sehari' => ['required', 'numeric', 'min:0'],
        ]);

        Setting::set('uang_saku_bulanan', $request->uang_saku_bulanan);
        Setting::set('potongan_per_alpha', $request->potongan_per_alpha);
        Setting::set('potongan_per_setengah_hari', $request->potongan_per_setengah_hari);
        Setting::set('jam_minimal_sehari', $request->jam_minimal_sehari);

        return back()->with('success', 'Pengaturan uang saku berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $data = $this->hitungRekap($bulan);

        $filename = "uang-saku-{$bulan}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama', 'NIM', 'Hari Kerja', 'Hadir Penuh', 'Setengah Hari', 'Izin', 'Sakit', 'Alpha', 'Potongan', 'Uang Saku Diterima']);
            foreach ($data['rekap'] as $r) {
                fputcsv($file, [
                    $r['user']->name, $r['user']->nim, $data['totalHariKerja'],
                    $r['hadir'], $r['setengah'], $r['izin'], $r['sakit'], $r['alpha'],
                    $r['potongan'], $r['diterima'],
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function hitungRekap(string $bulan): array
    {
        [$tahun, $bln] = explode('-', $bulan);

        $awalBulan = Carbon::createFromDate((int) $tahun, (int) $bln, 1)->startOfDay();
        $akhirBulan = $awalBulan->copy()->endOfMonth();
        if ($akhirBulan->greaterThan(now())) {
            $akhirBulan = now()->endOfDay();
        }

        $hariKerja = [];
        $cursor = $awalBulan->copy();
        while ($cursor->lte($akhirBulan)) {
            if (!$cursor->isWeekend()) {
                $hariKerja[] = $cursor->toDateString();
            }
            $cursor->addDay();
        }
        $totalHariKerja = count($hariKerja);

        $uangSaku = (int) Setting::get('uang_saku_bulanan', 2000000);
        $potonganAlpha = (int) Setting::get('potongan_per_alpha', 100000);
        $potonganSetengah = (int) Setting::get('potongan_per_setengah_hari', 50000);
        $jamMinimal = (float) Setting::get('jam_minimal_sehari', 6);

        $mahasiswa = User::where('role', 'mahasiswa')->orderBy('name')->get();

        $rekap = [];
        foreach ($mahasiswa as $mhs) {
            $entries = LogbookEntry::where('user_id', $mhs->id)
                ->whereIn('tanggal', $hariKerja)
                ->get()
                ->keyBy(fn ($e) => $e->tanggal->toDateString());

            $hadirPenuh = 0; $setengahHari = 0; $izin = 0; $sakit = 0; $alpha = 0;

            foreach ($hariKerja as $tgl) {
                $entry = $entries->get($tgl);

                if (!$entry) {
                    $alpha++;
                    continue;
                }

                if ($entry->tipe === 'izin' && $entry->status !== 'ditolak') {
                    $izin++;
                } elseif ($entry->tipe === 'sakit' && $entry->status !== 'ditolak') {
                    $sakit++;
                } elseif ($entry->tipe === 'hadir') {
                    $jam = $entry->durasi_menit / 60;
                    if ($jam >= $jamMinimal) {
                        $hadirPenuh++;
                    } else {
                        $setengahHari++;
                    }
                } else {
                    $alpha++;
                }
            }

            $potongan = ($alpha * $potonganAlpha) + ($setengahHari * $potonganSetengah);
            $diterima = max($uangSaku - $potongan, 0);

            $rekap[] = [
                'user' => $mhs,
                'hadir' => $hadirPenuh,
                'setengah' => $setengahHari,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'potongan' => $potongan,
                'diterima' => $diterima,
            ];
        }

        return compact('rekap', 'totalHariKerja', 'uangSaku', 'potonganAlpha', 'potonganSetengah');
    }
}