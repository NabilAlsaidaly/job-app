<x-main-layout title="شَغّلني - بوابتك نحو المستقبل المهني">

    {{-- العنوان الرئيسي المتحرك --}}
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
        <h1 class="text-4xl font-bold text-center text-white mb-6 transition-all duration-700 tracking-tight"
            x-bind:class="show ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'">
            Welcome to Shaghilni
        </h1>
    </div>

    {{-- النص التعريفي المتحرك --}}
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 450)">
        <p class="text-lg text-center text-gray-300 transition-all duration-700 max-w-2xl mx-auto leading-relaxed font-medium tracking-wide"
            x-bind:class="show ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'">
            Discover the right job opportunities and achieve your career goals with us
        </p>
    </div>

    {{-- النص المستخرج من الصورة --}}
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 700)">
        <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            class="mt-12 text-center">

            <p class="text-gray-400 text-lg mb-8 max-w-xl mx-auto leading-relaxed font-medium">
                Connect with top employers and explore exciting opportunities tailored for you.
            </p>

            <div class="flex justify-center gap-6">
                <a href="{{ route('register') }}"
                    class="rounded-lg bg-white/10 hover:bg-white/20 px-6 py-3 text-white transition-colors font-semibold tracking-wide">
                    Create an Account
                </a>

                <a href="{{ route('login') }}"
                    class="rounded-lg bg-gradient-to-r from-indigo-500 to-rose-500 hover:from-indigo-600 hover:to-rose-600 px-6 py-3 text-white transition-colors font-semibold tracking-wide">
                    Login
                </a>
            </div>
        </div>
    </div>

</x-main-layout>
