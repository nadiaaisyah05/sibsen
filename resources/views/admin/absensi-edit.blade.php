<x-layout>
    <h1 class="text-2xl font-extrabold mb-5">Edit Data Absensi (Admin)</h1>
    <form action="{{ route('admin.absensi.update', $entry) }}" method="POST" class="bg-white border border-[#E6E9F2] rounded-2xl p-6 max-w-xl">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Mahasiswa</label>
            <input type="text" value="{{ $entry->user->name }}" disabled class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm bg-[#F2F4FA]">
        </div>
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $entry->tanggal->format('Y-m-d')) }}" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Jam Masuk</label>
                <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $entry->jam_mulai) }}" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Jam Keluar</label>
                <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $entry->jam_selesai) }}" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
            </div>
        </div>
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Judul Aktivitas</label>
            <input type="text" name="judul" value="{{ old('judul', $entry->judul) }}" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
        </div>
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">{{ old('deskripsi', $entry->deskripsi) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Status</label>
            <select name="status" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
                <option value="menunggu" @selected($entry->status==='menunggu')>Menunggu</option>
                <option value="disetujui" @selected($entry->status==='disetujui')>Disetujui</option>
                <option value="ditolak" @selected($entry->status==='ditolak')>Ditolak</option>
            </select>
        </div>
        <div class="flex justify-end gap-2.5">
            <a href="{{ route('admin.absensi') }}" class="border border-[#E6E9F2] px-4 py-2.5 rounded-lg text-sm font-semibold">Batal</a>
            <button class="bg-[#131B3E] text-white px-4 py-2.5 rounded-lg text-sm font-semibold">Simpan</button>
        </div>
    </form>
</x-layout>