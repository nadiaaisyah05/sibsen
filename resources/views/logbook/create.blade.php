<x-layout>
    <h1 class="text-2xl font-extrabold mb-5">Tambah Entri Logbook</h1>

    <form action="{{ route('logbook.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-[#E6E9F2] rounded-2xl p-6 max-w-xl">
        @csrf
        @include('logbook._form')
        <div class="flex justify-end gap-2.5 mt-2">
            <a href="{{ route('logbook.index') }}" class="border border-[#E6E9F2] px-4 py-2.5 rounded-lg text-sm font-semibold">Batal</a>
            <button class="bg-[#131B3E] text-white px-4 py-2.5 rounded-lg text-sm font-semibold">Simpan Entri</button>
        </div>
    </form>
</x-layout>