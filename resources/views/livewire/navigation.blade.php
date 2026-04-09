<head>
    @include('partials.head')
</head>

<header class="fixed top-6 left-0 right-0 z-50 flex justify-center px-4" wire:ignore>
    <nav class="flex items-center justify-between w-full max-w-4xl h-14 px-4 bg-white/70 backdrop-blur-xl border border-zinc-200/50 rounded-full shadow-sm">

        {{-- Logo --}}
        <div class="flex items-center gap-2 pl-2">
            <a href="{{ route('nlah.home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <img src="/image/logo.png" class="w-8 h-8 rounded-full object-cover" alt="NLAH Logo">
                <span class="font-bold tracking-tight text-sm md:text-base text-gray-900">NLAH</span>
            </a>
        </div>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center gap-6 text-[13px] font-medium text-zinc-600">
            <a href="{{ route('nlah.home') }}"
               class="{{ request()->routeIs('nlah.home') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                Home
            </a>
            <a href="{{ route('nlah.services') }}"
               class="{{ request()->routeIs('nlah.services') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                Services
            </a>
            <a href="{{ route('nlah.about') }}"
               class="{{ request()->routeIs('nlah.about') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                About Us
            </a>
            <a href="{{ route('nlah.news') }}"
               class="{{ request()->routeIs('nlah.news') ? 'text-black font-semibold' : 'hover:text-black transition-colors' }}">
                Events
            </a>

            {{-- Desktop Online Services Dropdown --}}
            <div class="relative" id="desktop-options-wrapper">
                <button onclick="toggleDesktopOptions()"
                        class="flex items-center gap-1 text-[13px] font-medium text-zinc-600 hover:text-black transition-colors">
                    Online Services
                    <svg id="desktop-options-chevron"
                         class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div id="desktop-options-menu"
                     style="display:none;"
                     class="absolute top-9 left-1/2 -translate-x-1/2 w-48 bg-white border border-zinc-200/60 rounded-xl shadow-lg overflow-hidden z-50 py-1">

                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                        <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        NLAH Portal
                    </a>


                    @auth
                    <div class="border-t border-zinc-100 mt-1 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-2">

            {{-- Facebook --}}
            <a href="https://www.facebook.com/nlahospitalinc" target="_blank"
               class="p-2 text-zinc-500 hover:text-black transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                </svg>
            </a>

            {{-- Mobile Hamburger --}}
            <div class="md:hidden" wire:ignore>
                <button id="mobile-menu-btn"
                        class="p-1.5 text-zinc-500 hover:text-black transition-colors"
                        aria-label="Toggle menu"
                        onclick="toggleMobileMenu()">
                    <svg id="icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="6" y2="6"/>
                        <line x1="4" x2="20" y1="12" y2="12"/>
                        <line x1="4" x2="20" y1="18" y2="18"/>
                    </svg>
                    <svg id="icon-close" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile Menu Panel --}}
    <div id="mobile-menu"
         style="display:none;"
         class="md:hidden absolute top-[4.5rem] left-4 right-4 bg-white border border-zinc-200/60 rounded-2xl shadow-xl overflow-hidden z-40">
        <div class="flex flex-col p-2">

            <a href="{{ route('nlah.home') }}" onclick="closeMobileMenu()"
               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('nlah.home') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                Home
            </a>
            <a href="{{ route('nlah.services') }}" onclick="closeMobileMenu()"
               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('nlah.services') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                Services
            </a>
            <a href="{{ route('nlah.about') }}" onclick="closeMobileMenu()"
               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('nlah.about') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                About Us
            </a>
            <a href="{{ route('nlah.news') }}" onclick="closeMobileMenu()"
               class="flex items-center px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('nlah.news') ? 'bg-zinc-100 text-black' : 'text-zinc-700 hover:bg-zinc-50 hover:text-black' }}">
                Events
            </a>

            {{-- Mobile Online Services Accordion --}}
            <div>
                <button onclick="toggleOptions()"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                    <span>Online Services</span>
                    <svg id="options-chevron"
                         class="w-4 h-4 text-zinc-400 transition-transform duration-200"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div id="options-body" style="display:none;"
                     class="ml-3 mb-1 flex flex-col border-l-2 border-zinc-100 pl-3">

                    <a href="{{ route('login') }}" onclick="closeMobileMenu()"
                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                        <svg class="w-4 h-4 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        NLAH Portal
                    </a>

                </div>
            </div>
        </div>

        @if (Route::has('login'))
        <div class="border-t border-zinc-100 p-2">
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="closeMobileMenu()"
                        class="flex items-center gap-2.5 w-full px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Log Out
                </button>
            </form>
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

    <livewire:bot/>
</header>

<script>
    function toggleMobileMenu() {
        const menu      = document.getElementById('mobile-menu');
        const hamburger = document.getElementById('icon-hamburger');
        const close     = document.getElementById('icon-close');
        const isOpen    = menu.style.display === 'block';

        menu.style.display      = isOpen ? 'none'  : 'block';
        hamburger.style.display = isOpen ? 'block' : 'none';
        close.style.display     = isOpen ? 'none'  : 'block';

        if (isOpen) {
            document.getElementById('options-body').style.display    = 'none';
            document.getElementById('options-chevron').style.transform = '';
        }
    }

    function closeMobileMenu() {
        document.getElementById('mobile-menu').style.display      = 'none';
        document.getElementById('icon-hamburger').style.display   = 'block';
        document.getElementById('icon-close').style.display       = 'none';
        document.getElementById('options-body').style.display     = 'none';
        document.getElementById('options-chevron').style.transform = '';
    }

    function toggleOptions() {
        const body    = document.getElementById('options-body');
        const chevron = document.getElementById('options-chevron');
        const isOpen  = body.style.display === 'block';
        body.style.display       = isOpen ? 'none'            : 'block';
        chevron.style.transform  = isOpen ? ''                : 'rotate(180deg)';
    }

    function toggleDesktopOptions() {
        const menu    = document.getElementById('desktop-options-menu');
        const chevron = document.getElementById('desktop-options-chevron');
        const isOpen  = menu.style.display === 'block';
        menu.style.display      = isOpen ? 'none'            : 'block';
        chevron.style.transform = isOpen ? ''                : 'rotate(180deg)';
    }

    document.addEventListener('click', function (e) {
        const header = document.querySelector('header');
        if (!header.contains(e.target)) {
            closeMobileMenu();
        }

        const desktopWrapper = document.getElementById('desktop-options-wrapper');
        if (desktopWrapper && !desktopWrapper.contains(e.target)) {
            document.getElementById('desktop-options-menu').style.display    = 'none';
            document.getElementById('desktop-options-chevron').style.transform = '';
        }
    });
</script>
