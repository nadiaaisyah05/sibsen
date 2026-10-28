<x-layout>
    <div class="flex justify-between items-end mb-6 gap-4 flex-wrap">
        <div>
            <h1 class="text-[26px] font-extrabold tracking-tight mb-1">Absensi & Logbook</h1>
            <p class="text-sm text-[#6B7280]">Presensi otomatis dari sistem, hanya bisa dilakukan di kawasan yang diizinkan.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('izinModal').classList.remove('hidden')" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Ajukan Izin/Sakit</button>
            <button onclick="window.print()" class="border border-[#E6E9F2] px-4 py-2.5 rounded-xl text-sm font-semibold">Print</button>
        </div>
    </div>

    <div class="bg-white border border-[#E6E9F2] rounded-2xl p-6 mb-6">
        <div id="locStatus" class="text-sm text-[#6B7280] mb-3">Mengecek lokasi...</div>
        <form id="checkinBox" action="{{ route('logbook.checkin') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="hidden" name="latitude" id="lat1">
            <input type="hidden" name="longitude" id="lng1">
            <input type="text" name="judul" placeholder="Judul aktivitas hari ini" required class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm mb-2">
            <textarea name="deskripsi" placeholder="Deskripsi aktivitas" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2 text-sm mb-2"></textarea>
            <input type="file" name="bukti" accept="image/*" class="text-sm mb-2">
            <button class="bg-[#131B3E] text-white px-4 py-2 rounded-lg text-sm font-semibold">Presensi Masuk</button>
        </form>

        <form action="{{ route('logbook.checkout') }}" method="POST" id="checkoutForm" class="hidden">
            @csrf
            <input type="hidden" name="latitude" id="lat2">
            <input type="hidden" name="longitude" id="lng2">
            <button class="bg-[#E0553F] text-white px-4 py-2 rounded-lg text-sm font-semibold">Presensi Keluar</button>
        </form>
    </div>

    @if(session('error'))
    <div class="bg-[#FCEBE8] text-[#E0553F] text-sm px-4 py-3 rounded-xl mb-5">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-[#E6E9F2] rounded-2xl p-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-left text-[10.5px] text-[#9CA3B5] font-bold uppercase border-b border-[#E6E9F2]">
                    <th class="pb-3 pr-3">Tanggal</th><th class="pb-3 pr-3">Tipe</th><th class="pb-3 pr-3">Aktivitas</th><th class="pb-3 pr-3">Jam</th><th class="pb-3 pr-3">Bukti</th><th class="pb-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $e)
                <tr class="border-b border-[#E6E9F2] last:border-0">
                    <td class="py-3 pr-3 text-sm font-bold">{{ $e->tanggal->format('d/m/Y') }}</td>
                    <td class="py-3 pr-3 text-sm capitalize">{{ $e->tipe }} @if($e->tipe!=='hadir')<span class="text-xs text-[#9CA3B5]">({{ $e->status }})</span>@endif</td>
                    <td class="py-3 pr-3 text-sm">{{ $e->judul }}</td>
                    <td class="py-3 pr-3 text-sm">{{ $e->jam_mulai ? substr($e->jam_mulai,0,5) : '-' }} - {{ $e->jam_selesai ? substr($e->jam_selesai,0,5) : '-' }}</td>
                    <td class="py-3 pr-3">@if($e->bukti)<a href="{{ asset('storage/'.$e->bukti) }}" target="_blank" class="text-xs underline">Buka</a>@else -@endif</td>
                    <td class="py-3">
                        <form action="{{ route('logbook.destroy', $e) }}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-[#E0553F]">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-sm text-[#9CA3B5]">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="izinModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="font-bold text-lg mb-4">Ajukan Izin / Sakit</h3>
            <form action="{{ route('logbook.izin') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="date" name="tanggal" required class="w-full border rounded-lg px-3 py-2 text-sm mb-2">
                <select name="tipe" required class="w-full border rounded-lg px-3 py-2 text-sm mb-2">
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
                <textarea name="deskripsi" placeholder="Keterangan" class="w-full border rounded-lg px-3 py-2 text-sm mb-2"></textarea>
                <input type="file" name="bukti" accept="image/*" class="text-sm mb-3">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')" class="border px-4 py-2 rounded-lg text-sm">Batal</button>
                    <button class="bg-[#131B3E] text-white px-4 py-2 rounded-lg text-sm">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude, lng = pos.coords.longitude;
        document.getElementById('lat1').value = lat;
        document.getElementById('lng1').value = lng;
        document.getElementById('lat2').value = lat;
        document.getElementById('lng2').value = lng;
        document.getElementById('locStatus').textContent = 'Lokasi terdeteksi. Silakan lakukan presensi.';
        document.getElementById('checkinBox').classList.remove('hidden');
        document.getElementById('checkoutForm').classList.remove('hidden');
    }, err => {
        document.getElementById('locStatus').textContent = 'Gagal mengambil lokasi: ' + err.message;
    }, { enableHighAccuracy: true });
    </script>
</x-layout>