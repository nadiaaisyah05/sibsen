<x-layout>
    <div class="flex justify-between items-end mb-6 gap-4 flex-wrap">
        <div>
            <h1 class="text-2xl font-extrabold mb-1">Rekap & Validasi Absensi (Admin)</h1>
            <p class="text-sm text-[#6B7280]">Pantau, filter, validasi, dan kelola data absensi seluruh mahasiswa.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.absensi.export', ['bulan' => $bulan]) }}" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Export CSV</a>
            <button onclick="window.print()" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Cetak</button>
        </div>
    </div>

    <!-- Dashboard ringkasan -->
    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Hadir</div>
            <div class="text-xl font-extrabold text-[#227A4F]">{{ $stats['hadir'] }}</div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Izin</div>
            <div class="text-xl font-extrabold text-[#B8860B]">{{ $stats['izin'] }}</div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Sakit</div>
            <div class="text-xl font-extrabold text-[#E0553F]">{{ $stats['sakit'] }}</div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-xl p-4">
            <div class="text-[10px] font-bold text-[#9CA3B5] uppercase mb-1">Menunggu Validasi</div>
            <div class="text-xl font-extrabold text-[#131B3E]">{{ $stats['menunggu'] }}</div>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="bg-white border border-[#E6E9F2] rounded-2xl p-5 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Periode</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Cari Nama</label>
            <input type="text" name="nama" value="{{ $nama }}" placeholder="Nama mahasiswa..." class="border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Tipe</label>
            <select name="tipe" class="border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                <option value="hadir" @selected($tipe==='hadir')>Hadir</option>
                <option value="izin" @selected($tipe==='izin')>Izin</option>
                <option value="sakit" @selected($tipe==='sakit')>Sakit</option>
            </select>
        </div>
        <button class="bg-[#131B3E] text-white px-4 py-2 rounded-lg text-sm font-semibold">Filter</button>
        <a href="{{ route('admin.absensi') }}" class="border border-[#E6E9F2] px-4 py-2 rounded-lg text-sm font-semibold">Reset</a>
    </form>

    <div class="bg-white border border-[#E6E9F2] rounded-2xl p-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-[10.5px] text-[#9CA3B5] font-bold uppercase border-b border-[#E6E9F2]">
                    <th class="pb-2 pr-2">Nama</th>
                    <th class="pb-2 pr-2">Tanggal</th>
                    <th class="pb-2 pr-2">Tipe</th>
                    <th class="pb-2 pr-2">Jam</th>
                    <th class="pb-2 pr-2">Aktivitas</th>
                    <th class="pb-2 pr-2">Bukti</th>
                    <th class="pb-2 pr-2">Status</th>
                    <th class="pb-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                <tr class="border-b border-[#E6E9F2] last:border-0 align-top">
                    <td class="py-3 pr-2 text-sm font-bold">{{ $e->user->name }}<div class="text-[11px] text-[#9CA3B5] font-normal">{{ $e->user->nim }}</div></td>
                    <td class="py-3 pr-2 text-sm">{{ $e->tanggal->format('d/m/Y') }}</td>
                    <td class="py-3 pr-2 text-sm capitalize">{{ $e->tipe }}</td>
                    <td class="py-3 pr-2 text-sm">{{ $e->jam_mulai ? substr($e->jam_mulai,0,5) : '-' }} - {{ $e->jam_selesai ? substr($e->jam_selesai,0,5) : '-' }}</td>
                    <td class="py-3 pr-2 text-sm max-w-[220px]">
                        <b class="block">{{ $e->judul }}</b>
                        <span class="text-[#6B7280] text-xs">{{ \Illuminate\Support\Str::limit($e->deskripsi, 80) }}</span>
                    </td>
                    <td class="py-3 pr-2">
                        @if($e->bukti)<a href="{{ asset('storage/'.$e->bukti) }}" target="_blank" class="text-xs underline">Buka</a>@else<span class="text-xs text-[#C7CEDD]">-</span>@endif
                    </td>
                    <td class="py-3 pr-2">
                        <span class="text-[11px] font-bold px-2 py-1 rounded-full
                            {{ $e->status === 'disetujui' ? 'bg-[#E7F6EE] text-[#227A4F]' : ($e->status === 'ditolak' ? 'bg-[#FCEBE8] text-[#E0553F]' : 'bg-[#FFF6E3] text-[#B8860B]') }}">
                            {{ ucfirst($e->status) }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="flex flex-col gap-1.5">
                            @if($e->status === 'menunggu')
                            <form action="{{ route('admin.absensi.validasi', $e) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="disetujui">
                                <button class="text-xs text-[#227A4F] font-semibold">✓ Setujui</button>
                            </form>
                            <form action="{{ route('admin.absensi.validasi', $e) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="ditolak">
                                <button class="text-xs text-[#E0553F] font-semibold">✕ Tolak</button>
                            </form>
                            @endif
                            <a href="{{ route('admin.absensi.edit', $e) }}" class="text-xs text-[#131B3E] font-semibold">✎ Edit</a>
                            <form action="{{ route('admin.absensi.destroy', $e) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-[#E0553F] font-semibold">🗑 Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-sm text-[#9CA3B5]">Tidak ada data pada periode/filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>