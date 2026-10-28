<x-layout>
    <h1 class="text-2xl font-extrabold mb-5">Manajemen Akun (Admin)</h1>
    <div class="bg-white border rounded-2xl p-6">
        <table class="w-full">
            <thead><tr class="text-left text-xs text-[#9CA3B5] border-b"><th class="pb-2">Nama</th><th class="pb-2">Email</th><th class="pb-2">Role</th><th class="pb-2">Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-b">
                    <td class="py-2 text-sm">{{ $u->name }}</td>
                    <td class="py-2 text-sm">{{ $u->email }}</td>
                    <td class="py-2 text-sm capitalize">{{ $u->role }}</td>
                    <td class="py-2">
                        @if($u->role !== 'admin')
                        <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-[#E0553F]">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>