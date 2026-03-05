<head>
    @include('partials.head')
</head>

<header class="fixed top-6 left-0 right-0 z-50 flex justify-center px-4" wire:ignore>
    <nav class="flex items-center justify-between w-full max-w-4xl h-14 px-4 bg-white/70 backdrop-blur-xl border border-zinc-200/50 rounded-full shadow-sm">

        {{-- Logo --}}
       <div class="flex items-center gap-2 pl-2">
            <a href="{{ route('nlah.home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <img src="/image/logo.png" 
                    class="w-8 h-8 rounded-full object-cover" 
                    alt="NLAH Logo">
                
                <span class="font-bold tracking-tight text-sm md:text-base text-gray-900">
                    NLAH
                </span>
            </a>
        </div>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center gap-6 text-[13px] font-medium text-zinc-600">
            <a href="{{ route('nlah.home') }}"
                class="{{ request()->routeIs('nlah.home') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                {{ __('Home') }}
            </a>
            <a href="{{ route('nlah.services') }}"
                class="{{ request()->routeIs('nlah.services') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                {{ __('Services') }}
            </a>

           {{-- Desktop Options Dropdown --}}
<div class="relative" id="desktop-options-wrapper">
    <button
        onclick="toggleDesktopOptions()"
        class="flex ps-4 pe-4 items-center gap-1 text-[13px] font-medium text-zinc-600 hover:text-black transition-colors"
    >
        HR Corner
        <svg id="desktop-options-chevron"
            class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </button>

    <div
        id="desktop-options-menu"
        style="display:none;"
        class="absolute top-8 left-0 w-44 bg-white border border-zinc-200/60 rounded-xl shadow-lg overflow-hidden z-50 py-1"
    >
        <ul class="flex flex-col">
            <li>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Leave Application</a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Pay-Off Application</a>
            </li>
            <li><hr class="my-1 border-zinc-100"></li>
            <li>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">Overtime/On-call Application</a>
            </li>
            
        </ul>
    </div>
</div>

            <a href="{{ route('nlah.about') }}"
                class="{{ request()->routeIs('nlah.about') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                {{ __('About Us') }}
            </a>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="hidden md:block px-4 py-1.5 border border-zinc-200 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden md:block px-4 py-1.5 border border-zinc-200 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="hidden md:block px-4 py-1.5 border border-zinc-200 rounded-lg text-sm font-medium hover:bg-zinc-50 transition-colors">
                            Register
                        </a>
                    @endif
                @endauth
            @endif

            {{-- Facebook --}}
            <a href="https://www.facebook.com/nlahospitalinc" target="_blank"
                class="p-2 text-zinc-500 hover:text-black transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                </svg>
            </a>

            {{-- Mobile Toggle Button --}}
            {{-- REPLACE WITH: wrap button + panel together --}}
<div class="md:hidden" wire:ignore>
    <button
        id="mobile-menu-btn"
        class="p-1.5 text-zinc-500 hover:text-black transition-colors"
        aria-label="Toggle menu"
        onclick="toggleMobileMenu()"
    >
        <svg id="icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" x2="20" y1="6" y2="6"/>
            <line x1="4" x2="20" y1="12" y2="12"/>
            <line x1="4" x2="20" y1="18" y2="18"/>
        </svg>
        <svg id="icon-close" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    {{-- Mobile Menu Panel -- now a sibling inside relative wrapper --}}
    <div
        id="mobile-menu"
        style="display:none; width:100%;"
        class="absolute mt-3 top-12 right-0  bg-white border border-zinc-200/60 rounded-2xl shadow-xl overflow-hidden z-50"
    >
        <div class="flex flex-col p-2">
            <a href="{{ route('nlah.home') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.home') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                Home
            </a>
            <a href="{{ route('nlah.services') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.services') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                Services
            </a>

            {{-- Options Accordion --}}
            <div>
                <button onclick="toggleOptions()"
                    class="w-full flex items-center px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                    <span class="flex items-center gap-1">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        HR Corner
                    </span>
                </button>
                
                <div id="options-body" style="display:none;" class="ml-4 mb-1 flex flex-col border-l-2 border-zinc-100 pl-3">
                    <a href="#" class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                        <li class="list-disc">Leave Application</li>
                    </a>
                    <a href="#" class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                        <li class="list-disc">Pay-Off Application</li>
                    </a>
                    <a href="#" class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                        <li class="list-disc">Overtime/On-call Application</li>
                    </a>
                </div>
            </div>

            <a href="{{ route('nlah.about') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.about') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                About Us
            </a>
             <a href="{{ route('nlah.about') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.about') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                About messssage
            </a>
        </div>

        @if (Route::has('login'))
            <div class="border-t border-zinc-100 p-2">
                @auth
                    <a href="{{ url('/dashboard') }}" onclick="closeMobileMenu()"
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                        Dashboard
                    </a>
                @else
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('login') }}" onclick="closeMobileMenu()"
                            class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" onclick="closeMobileMenu()"
                                class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold bg-black text-white hover:bg-zinc-800 transition-colors">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        @endif
    </div>
</div>
        </div>
    </nav>

    {{-- Mobile Menu Panel --}}
    <div
        id="mobile-menu"
        style="display:none;"
        class="md:hidden absolute top-[4.5rem] right-4 w-full bg-white border border-zinc-200/60 rounded-2xl shadow-xl overflow-hidden"
    >
        <div class="flex flex-col p-2">

            <a href="{{ route('nlah.home') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.home') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                Home
            </a>

            <a href="{{ route('nlah.services') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.services') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                Services
            </a>

            {{-- Options Accordion --}}
            <div>
                <button
                    onclick="toggleOptions()"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors"
                >
                    <span>HR Corner</span>
                    <svg id="options-chevron"
                        class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div id="options-body" style="display:none;" class="ml-4 mb-1 border-l-2 border-zinc-100 pl-3">
                    <ul class="flex flex-col gap-1">
                        <li>
                            <a href="#"
                               class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                                View
                            </a>
                        </li>
                        <li>
                            <a href="#"
                               class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                                Transfer
                            </a>
                        </li>
                        <li><hr class="my-1 border-zinc-100"></li>
                        <li>
                            <a href="#"
                               class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                                Publish
                            </a>
                        </li>
                        <li>
                            <a href="#"
                               class="px-3 py-2.5 rounded-lg text-sm text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors">
                                Share
                            </a>
                        </li>
                        <li><hr class="my-1 border-zinc-100"></li>
                        <li>
                            <a href="#"
                               class="px-3 py-2.5 rounded-lg text-sm text-red-500 hover:bg-red-50 transition-colors">
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="{{ route('nlah.about') }}"
                class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors
                    {{ request()->routeIs('nlah.about') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}"
                onclick="closeMobileMenu()">
                About Us
            </a>
           
        </div>

        @if (Route::has('login'))
            <div class="border-t border-zinc-100 p-2">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        onclick="closeMobileMenu()"
                        class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                        Dashboard
                    </a>
                @else
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('login') }}"
                            onclick="closeMobileMenu()"
                            class="flex items-center px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                onclick="closeMobileMenu()"
                                class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-semibold bg-black text-white hover:bg-zinc-800 transition-colors">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        @endif
    </div>
</header>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const hamburger = document.getElementById('icon-hamburger');
        const close = document.getElementById('icon-close');
        const isOpen = menu.style.display === 'block';

        menu.style.display = isOpen ? 'none' : 'block';
        hamburger.style.display = isOpen ? 'block' : 'none';
        close.style.display = isOpen ? 'none' : 'block';

        // Close options accordion when closing menu
        if (isOpen) {
            document.getElementById('options-body').style.display = 'none';
            document.getElementById('options-chevron').style.transform = '';
        }
    }

    function closeMobileMenu() {
        document.getElementById('mobile-menu').style.display = 'none';
        document.getElementById('icon-hamburger').style.display = 'block';
        document.getElementById('icon-close').style.display = 'none';
        document.getElementById('options-body').style.display = 'none';
        document.getElementById('options-chevron').style.transform = '';
    }

    function toggleOptions() {
        const body = document.getElementById('options-body');
        const chevron = document.getElementById('options-chevron');
        const isOpen = body.style.display === 'block';

        body.style.display = isOpen ? 'none' : 'block';
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        const header = document.querySelector('header');
        if (!header.contains(e.target)) {
            closeMobileMenu();
        }
    });
</script>
<script>
    function toggleDesktopOptions() {
    const menu = document.getElementById('desktop-options-menu');
    const chevron = document.getElementById('desktop-options-chevron');
    const isOpen = menu.style.display === 'block';
    menu.style.display = isOpen ? 'none' : 'block';
    chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
}

// Update the existing outside-click listener to also close desktop dropdown
document.addEventListener('click', function(e) {
    // Close mobile menu
    const header = document.querySelector('header');
    if (!header.contains(e.target)) {
        closeMobileMenu();
    }

    // Close desktop options if clicking outside it
    const desktopWrapper = document.getElementById('desktop-options-wrapper');
    if (desktopWrapper && !desktopWrapper.contains(e.target)) {
        document.getElementById('desktop-options-menu').style.display = 'none';
        document.getElementById('desktop-options-chevron').style.transform = '';
    }
});
</script>