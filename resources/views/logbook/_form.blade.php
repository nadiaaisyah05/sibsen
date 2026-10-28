@php $entry = $entry ?? null; @endphp

<div class="mb-5">
    <label class="text-xs font-bold text-[#6B7280] block mb-2">Tanggal</label>
    <input type="date" name="tanggal" value="{{ old('tanggal', $entry->tanggal->format('Y-m-d') ?? '') }}"
           class="w-full border border-[#E6E9F2] rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#131B3E]/15 focus:border-[#131B3E] transition-all">
    @error('tanggal') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4 mb-5">
    <div>
        <label class="text-xs font-bold text-[#6B7280] block mb-2">Jam Mulai</label>
        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $entry->jam_mulai ?? '07:30') }}"
               class="w-full border border-[#E6E9F2] rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#131B3E]/15 focus:border-[#131B3E] transition-all">
    </div>
    <div>
        <label class="text-xs font-bold text-[#6B7280] block mb-2">Jam Selesai</label>
        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $entry->jam_selesai ?? '16:30') }}"
               class="w-full border border-[#E6E9F2] rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#131B3E]/15 focus:border-[#131B3E] transition-all">
    </div>
</div>

<div class="mb-5">
    <label class="text-xs font-bold text-[#6B7280] block mb-2">Judul Aktivitas</label>
    <input type="text" name="judul" value="{{ old('judul', $entry->judul ?? '') }}"
           placeholder="Contoh: Membantu pengecekan dokumen LSP"
           class="w-full border border-[#E6E9F2] rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#131B3E]/15 focus:border-[#131B3E] transition-all">
    @error('judul') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
</div>

<div class="mb-5">
    <label class="text-xs font-bold text-[#6B7280] block mb-2">Deskripsi</label>
    <textarea name="deskripsi" rows="3" placeholder="Jelaskan aktivitas secara singkat..."
              class="w-full border border-[#E6E9F2] rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#131B3E]/15 focus:border-[#131B3E] transition-all resize-none">{{ old('deskripsi', $entry->deskripsi ?? '') }}</textarea>
</div>

<div class="mb-2">
    <label class="text-xs font-bold text-[#6B7280] block mb-2">Bukti (opsional, gambar)</label>
    <div class="flex items-center gap-3">
        <label class="flex-1 flex items-center gap-2.5 border border-dashed border-[#C7CEDD] rounded-xl px-3.5 py-3 text-sm text-[#9CA3B5] cursor-pointer hover:border-[#131B3E] hover:text-[#131B3E] transition-colors">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 flex-shrink-0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
            <span>Pilih gambar bukti...</span>
            <input type="file" name="bukti" accept="image/*" class="hidden">
        </label>
        @if($entry && $entry->bukti)
            <img src="{{ asset('storage/'.$entry->bukti) }}" class="w-12 h-12 rounded-xl object-cover border border-[#E6E9F2] flex-shrink-0">
        @endif
    </div>
    @error('bukti') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
</div>