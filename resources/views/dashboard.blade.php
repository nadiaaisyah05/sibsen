<x-layout>
    <div class="bg-gradient-to-br from-[#131B3E] to-[#1E2C63] rounded-2xl p-6 text-white mb-6">
        <div class="text-xl font-extrabold mb-1">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }} 👋</div>
        <div class="text-sm text-[#B7C0EA]">Lakukan presensi dan catat aktivitas magangmu di sini.</div>
    </div>

    <div class="grid grid-cols-2 gap-4 max-w-md mb-8">
        <div class="bg-white border border-[#E6E9F2] rounded-2xl p-5">
            <div class="text-[10.5px] font-bold text-[#9CA3B5] uppercase mb-1">Total Jam Kehadiran</div>
            <div class="text-2xl font-extrabold">{{ floor($totalMenit/60) }}<span class="text-sm text-[#9CA3B5]"> jam</span></div>
        </div>
        <div class="bg-white border border-[#E6E9F2] rounded-2xl p-5">
            <div class="text-[10.5px] font-bold text-[#9CA3B5] uppercase mb-1">Total Catatan</div>
            <div class="text-2xl font-extrabold">{{ $entries->count() }}</div>
        </div>
    </div>

    <div class="font-extrabold text-[15px] mb-3">Aktivitas Terbaru</div>
    <div class="bg-white border border-[#E6E9F2] rounded-2xl overflow-hidden">
        @forelse($recent as $e)
        <div class="flex items-center gap-4 px-5 py-4 border-b border-[#E6E9F2] last:border-0">
            <div class="w-14 text-center bg-[#F2F4FA] rounded-xl py-2">
                <div class="text-base font-extrabold">{{ $e->tanggal->format('d') }}</div>
                <div class="text-[9.5px] text-[#9CA3B5] uppercase font-bold">{{ $e->tanggal->translatedFormat('M') }}</div>
            </div>
            <div class="flex-1">
                <b class="text-sm block">{{ $e->judul }}</b>
                <span class="text-xs text-[#6B7280] capitalize">{{ $e->tipe }}</span>
            </div>
            <div class="text-xs font-bold bg-[#131B3E] text-white px-2.5 py-1 rounded-full">{{ $e->durasi_label }}</div>
        </div>
        @empty
        <div class="text-center py-12 text-sm text-[#9CA3B5]">Belum ada aktivitas.</div>
        @endforelse
    </div>
</x-layout>