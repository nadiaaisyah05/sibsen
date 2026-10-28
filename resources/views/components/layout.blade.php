<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'SIBSEN' }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="bg-[#F2F4FA] text-[#12162B] font-sans">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 shrink-0 bg-[#131B3E] flex flex-col p-4 sticky top-0 h-screen">

        {{-- LOGO --}}
        <div class="flex items-center gap-2 px-2 pb-6">
            <img
                src="{{ asset('images/logo2.png') }}"
                alt="Logo SIBSEN"
                class="w-10 h-10 rounded-xl object-contain bg-white p-1"
            >

            <div>
                <div class="text-white font-bold text-sm leading-tight">
                    SIBSEN
                </div>

                <div class="text-[#8791BB] text-xs">
                    Absensi &amp; Logbook Magang
                </div>
            </div>
        </div>

        {{-- MENU ADMIN --}}
        @if(auth()->user()->role === 'admin')

            <a
                href="{{ route('admin.absensi') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('admin.absensi') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
            >
                Rekap Absensi
            </a>

            <a
                href="{{ route('admin.users') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('admin.users') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
            >
                Manajemen Akun
            </a>

            <a
                href="{{ route('admin.uangsaku') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('admin.uangsaku') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
            >
                Rekap Uang Saku
            </a>

        {{-- MENU MAHASISWA --}}
        @else

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('dashboard') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
            >
                Dashboard
            </a>

            <a
                href="{{ route('logbook.index') }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
                {{ request()->routeIs('logbook.*') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
            >
                Absensi &amp; Logbook
            </a>

        @endif

        {{-- MENU BAGIAN BAWAH --}}
        <div class="flex-1"></div>

        <a
            href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium
            {{ request()->routeIs('profile.*') ? 'bg-[#1D2A5C] text-white' : 'text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white' }}"
        >
            Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full text-left flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-[#AEB7DE] hover:bg-[#1D2A5C]/60 hover:text-white"
            >
                Logout
            </button>
        </form>

    </aside>

    {{-- AREA KONTEN UTAMA --}}
    <div class="flex-1 min-w-0">

        {{-- HEADER --}}
        <header class="bg-white border-b border-[#E6E9F2] px-8 py-4 flex items-center justify-end gap-4">

            <div class="text-right">
                <div class="text-xs font-bold">
                    {{ strtoupper(auth()->user()->name) }}
                </div>

                <div class="text-[11px] text-[#9CA3B5]">
                    {{ auth()->user()->nim ?? '-' }}
                    ·
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>

            <div class="w-10 h-10 rounded-full bg-[#131B3E] text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

        </header>

        {{-- KONTEN HALAMAN --}}
        <main class="p-8">

            {{ $slot }}

        </main>

    </div>

</div>

</body>
</html>
