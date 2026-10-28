<x-layout>
    <h1 class="text-2xl font-extrabold mb-5">Edit Aktivitas</h1>
    <form action="{{ route('logbook.update', $entry) }}" method="POST" class="bg-white border border-[#E6E9F2] rounded-2xl p-6 max-w-xl">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Judul Aktivitas</label>
            <input type="text" name="judul" value="{{ old('judul', $entry->judul) }}" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">
        </div>
        <div class="mb-4">
            <label class="text-xs font-bold text-[#6B7280] block mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="w-full border border-[#E6E9F2] rounded-lg px-3 py-2.5 text-sm">{{ old('deskripsi', $entry->deskripsi) }}</textarea>
        </div>
        <div class="flex justify-end gap-2.5">
            <a href="{{ route('logbook.index') }}" class="border border-[#E6E9F2] px-4 py-2.5 rounded-lg text-sm font-semibold">Batal</a>
            <button class="bg-[#131B3E] text-white px-4 py-2.5 rounded-lg text-sm font-semibold">Simpan</button>
        </div>
    </form>
</x-layout>