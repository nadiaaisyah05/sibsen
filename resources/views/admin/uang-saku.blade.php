<x-layout>
    <div class="flex justify-between items-end mb-6 gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-extrabold mb-1">Rekap Uang Saku (Admin)</h1>
            <p class="text-sm text-[#6B7280]">Potongan dihitung otomatis dari hari alpha dan setengah hari dalam satu bulan.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.uangsaku.export', ['bulan' => $bulan]) }}" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Export CSV</a>
            <button onclick="window.print()" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Cetak</button>
        </div>
    </div>

    <form method="GET" class="bg-white border border-[#E6E9F2] rounded-2xl p-5 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Periode</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <button class="bg-[#131B3E] text-white px-4 py-2 rounded-lg text-sm font-semibold">Tampilkan</button>
    </form>

    <details class="bg-white border border-[#E6E9F2] rounded-2xl p-5 mb-6">
    <summary class="text-sm font-bold cursor-pointer select-none">⚙️ Pengaturan Nominal Uang Saku & Potongan</summary>
    <form method="POST" action="{{ route('admin.uangsaku.settings') }}" class="mt-4 grid grid-cols-2 gap-4">
        @csrf @method('PATCH')
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Uang Saku Penuh / Bulan (Rp)</label>
            <input type="number" name="uang_saku_bulanan" value="{{ $uangSaku }}" required class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Jam Minimal Dianggap Hadir Penuh</label>
            <input type="number" name="jam_minimal_sehari" value="{{ \App\Models\Setting::get('jam_minimal_sehari', 6) }}" required step="0.5" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Potongan per Hari Alpha (Rp)</label>
            <input type="number" name="potongan_per_alpha" value="{{ $potonganAlpha }}" required class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Potongan per Setengah Hari (Rp)</label>
            <input type="number" name="potongan_per_setengah_hari" value="{{ $potonganSetengah }}" required class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="col-span-2 flex justify-end">
            <button class="bg-[#131B3E] text-white px-5 py-2 rounded-lg text-sm font-semibold">Simpan Pengaturan</button>
        </div>
    </form>
</details>

    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Total Hari Kerja (Periode)</div>
            <div class="text-xl font-extrabold">{{ $totalHariKerja }} hari</div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Uang Saku Penuh / Bulan</div>
            <div class="text-xl font-extrabold">Rp{{ number_format($uangSaku, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Potongan / Alpha · Setengah Hari</div>
            <div class="text-sm font-extrabold">Rp{{ number_format($potonganAlpha, 0, ',', '.') }} · Rp{{ number_format($potonganSetengah, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white border border-[#E6E9F2] rounded-2xl p-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-[10.5px] text-[#9CA3B5] font-bold uppercase border-b border-[#E6E9F2]">
                    <th class="pb-2 pr-2">Nama</th>
                    <th class="pb-2 pr-2 text-center">Hadir</th>
                    <th class="pb-2 pr-2 text-center">½ Hari</th>
                    <th class="pb-2 pr-2 text-center">Izin</th>
                    <th class="pb-2 pr-2 text-center">Sakit</th>
                    <th class="pb-2 pr-2 text-center">Alpha</th>
                    <th class="pb-2 pr-2 text-right">Potongan</th>
                    <th class="pb-2 text-right">Diterima</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $r)
                <tr class="border-b border-[#E6E9F2] last:border-0">
                    <td class="py-3 pr-2 text-sm font-bold">{{ $r['user']->name }}<div class="text-[11px] text-[#9CA3B5] font-normal">{{ $r['user']->nim }}</div></td>
                    <td class="py-3 pr-2 text-sm text-center text-[#227A4F] font-bold">{{ $r['hadir'] }}</td>
                    <td class="py-3 pr-2 text-sm text-center text-[#B8860B] font-bold">{{ $r['setengah'] }}</td>
                    <td class="py-3 pr-2 text-sm text-center">{{ $r['izin'] }}</td>
                    <td class="py-3 pr-2 text-sm text-center">{{ $r['sakit'] }}</td>
                    <td class="py-3 pr-2 text-sm text-center text-[#E0553F] font-bold">{{ $r['alpha'] }}</td>
                    <td class="py-3 pr-2 text-sm text-right text-[#E0553F]">- Rp{{ number_format($r['potongan'], 0, ',', '.') }}</td>
                    <td class="py-3 text-sm text-right font-extrabold">Rp{{ number_format($r['diterima'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-sm text-[#9CA3B5]">Belum ada mahasiswa terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>