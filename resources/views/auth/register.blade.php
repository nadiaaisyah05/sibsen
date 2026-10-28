<x-guest-layout>
    @if ($errors->any())
        <div class="mb-4 bg-[#FCEBE8] text-[#E0553F] text-sm px-4 py-3 rounded-lg">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-xs font-bold text-[#6B7280] mb-1.5">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-xs font-bold text-[#6B7280] mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-xs font-bold text-[#6B7280] mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-xs font-bold text-[#6B7280] mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-[#F2F4FA] border-0 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#131B3E]/20">
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="bg-[#131B3E] text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-[#1D2A5C]">
                Register
            </button>
        </div>
    </form>

    <div class="text-xs text-center text-[#9CA3B5] mt-6 pt-5 border-t border-[#E6E9F2]">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-[#131B3E] font-bold underline">Log In</a>
    </div>
</x-guest-layout>