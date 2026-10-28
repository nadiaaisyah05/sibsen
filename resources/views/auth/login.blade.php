<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 bg-[#FCEBE8] text-[#E0553F] text-sm px-4 py-3 rounded-lg">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-xs font-bold text-[#6B7280] mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="mb-3">
            <label for="password" class="block text-xs font-bold text-[#6B7280] mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="flex items-center mb-5">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-[#E6E9F2] text-[#131B3E] focus:ring-[#131B3E]">
            <label for="remember_me" class="ms-2 text-sm text-[#6B7280]">Ingat saya</label>
        </div>

        <div class="flex items-center justify-between gap-3">
            @if (Route::has('password.request'))
                <a class="text-xs text-[#6B7280] underline hover:text-[#131B3E]" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif

            <button type="submit" class="bg-[#131B3E] text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#1D2A5C]">
                Log In
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-xs text-center text-[#9CA3B5] mt-6 pt-5 border-t border-[#E6E9F2]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#131B3E] font-bold underline">Register</a>
        </div>
    @endif
</x-guest-layout>