@include('partials.head')
<livewire:navigation/>
<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">
    <div class="flex flex-col md:flex-row gap-12 md:gap-24 items-start">
      <div class="flex-1 space-y-8">
        <h1 class="text-5xl md:text-7xl font-bold tracking-tight leading-[1.05]">
          Northern Luzon Adventist Hospital INC.
        </h1>
        <p class="text-xl md:text-3xl text-zinc-500 font-medium max-w-lg leading-snug">
          Artacho, Sison, Pangasinan
        </p>
        <div class="flex flex-wrap gap-3">
          <a href="https://maps.google.com/?cid=2854199161210118637&g_mp=CiVnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLkdldFBsYWNl" target="_blank" class="px-6 py-3 bg-[#e8dec9] text-zinc-800 rounded-lg font-medium hover:bg-[#dfd2b5] transition-colors inline-block">
            View on Maps
          </a> 
        </div>
      </div>

      <div class="relative flex-1">
        <div class="hidden md:block absolute -left-12 top-0 bottom-0 border-l border-dashed border-zinc-300"></div>
        <div class="md:hidden w-full border-t border-dashed border-zinc-300 mb-12"></div>

        <div class="space-y-10">
          <div class="flex gap-4">
            <div class="mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-800"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="1"/></svg></div>
            <div>
              <h3 class="font-bold text-lg">MISSION</h3>
              <p class="text-zinc-500 leading-relaxed">Sharing Jesus Christ Healing Ministry</p>
              <p class="text-zinc-500 leading-relaxed">Good Heart</p>
            </div>
          </div>

          <div class="flex gap-4">
            <div class="mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-800"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></div>
            <div>
              <h3 class="font-bold text-lg">VISION</h3>
              <p class="text-zinc-500 leading-relaxed">The Center of Excellence in Faith-based Healthcare, Education and Lifestyle.</p>
            </div>
          </div>

          <div class="flex gap-4">
            <div class="mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-800"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/><path d="M2 7h20"/><path d="M15 22V10"/><path d="M9 22V10"/></svg></div>
            <div>
              <h3 class="font-bold text-lg">CORE VALUES</h3>
              <p class="text-zinc-500 leading-relaxed">Integrity <br> Compassion <br> Excellence <br> Stewardship</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

<section class="max-w-7xl mx-auto px-4 sm:px-6 mb-20">
    <div class="flex items-center justify-between mb-8">
        <flux:heading size="xl" level="2">Our Facilities</flux:heading>
        
        <div class="flex gap-2">
            <button 
                onclick="document.getElementById('facilities-scroll').scrollBy({ left: -320, behavior: 'smooth' })"
                class="p-2 rounded-lg border border-zinc-200 hover:bg-zinc-100 transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button 
                onclick="document.getElementById('facilities-scroll').scrollBy({ left: 320, behavior: 'smooth' })"
                class="p-2 rounded-lg border border-zinc-200 hover:bg-zinc-100 transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div 
        id="facilities-scroll"
        class="flex gap-4 sm:gap-6 overflow-x-auto snap-x snap-mandatory pb-4"
        style="scrollbar-width: none; -ms-overflow-style: none;"
    >
        {{-- Slide 1 --}}
        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=1200" 
                    class="w-full h-full object-cover" 
                    alt="Hospital Interior"
                >
            </div>
            <div>
                <flux:heading size="lg">Modern Facilities</flux:heading>
                <flux:subheading>High-quality healthcare environments designed for patient comfort.</flux:subheading>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1581594658210-c5c85ad9a0e5?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Diagnostic Center"
                >
            </div>
            <div>
                <flux:heading size="lg">Diagnostic Center</flux:heading>
                <flux:subheading>Advanced laboratory and imaging services.</flux:subheading>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Emergency"
                >
            </div>
            <div>
                <flux:heading size="lg">24/7 Emergency</flux:heading>
                <flux:subheading>Ready to serve you at any hour of the day.</flux:subheading>
            </div>
        </div>

        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Emergency"
                >
            </div>
            <div>
                <flux:heading size="lg">24/7 Emergency</flux:heading>
                <flux:subheading>Ready to serve you at any hour of the day.</flux:subheading>
            </div>
        </div>

        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Emergency"
                >
            </div>
            <div>
                <flux:heading size="lg">24/7 Emergency</flux:heading>
                <flux:subheading>Ready to serve you at any hour of the day.</flux:subheading>
            </div>
        </div>

        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Emergency"
                >
            </div>
            <div>
                <flux:heading size="lg">24/7 Emergency</flux:heading>
                <flux:subheading>Ready to serve you at any hour of the day.</flux:subheading>
            </div>
        </div>

        <div class="snap-start shrink-0 w-[80vw] sm:w-[360px] lg:w-[400px] space-y-3">
            <div class="h-48 sm:h-56 w-full overflow-hidden rounded-xl sm:rounded-2xl bg-zinc-100">
                <img 
                    src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=800" 
                    class="w-full h-full object-cover" 
                    alt="Emergency"
                >
            </div>
            <div>
                <flux:heading size="lg">24/7 Emergency</flux:heading>
                <flux:subheading>Ready to serve you at any hour of the day.</flux:subheading>
            </div>
        </div>
    </div>
  </section>
  <livewire:footer/>