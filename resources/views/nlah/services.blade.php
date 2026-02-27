@include('partials.head')
<livewire:navigation/>
<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">
    <!-- Carousel Section -->
    <div class="relative mb-12" id="services-carousel">
        <!-- Carousel container -->
        <div class="relative h-96 rounded-2xl overflow-hidden">
            <!-- Slide 1 -->
            <div class="absolute inset-0 carousel-slide">
                <img src="/image/service1.jpg" 
                     alt="Slide 1" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
                    <div class="absolute bottom-0 left-0 p-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Welcome to Our Services</h2>
                        <p class="text-white/90 text-lg">Discover amazing features and possibilities</p>
                    </div>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="absolute inset-0 carousel-slide hidden">
                <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" 
                     alt="Slide 2" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
                    <div class="absolute bottom-0 left-0 p-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Innovative Solutions</h2>
                        <p class="text-white/90 text-lg">Cutting-edge technology at your fingertips</p>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="absolute inset-0 carousel-slide hidden">
                <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" 
                     alt="Slide 3" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
                    <div class="absolute bottom-0 left-0 p-8">
                        <h2 class="text-3xl font-bold text-white mb-2">Join Our Community</h2>
                        <p class="text-white/90 text-lg">Connect with like-minded professionals</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation buttons -->
        <button id="carousel-prev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button id="carousel-next" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        
        <!-- Indicators -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2" id="carousel-indicators">
            <button type="button" data-slide="0" class="carousel-indicator w-6 h-2 rounded-full bg-white transition-all duration-200"></button>
            <button type="button" data-slide="1" class="carousel-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-200"></button>
            <button type="button" data-slide="2" class="carousel-indicator w-2 h-2 rounded-full bg-white/50 hover:bg-white/75 transition-all duration-200"></button>
        </div>
    </div>
</main>
  <livewire:footer/>

<script>
    (function () {
        const root = document.getElementById('services-carousel');
        if (!root) return;

        const slides = root.querySelectorAll('.carousel-slide');
        const indicators = root.querySelectorAll('.carousel-indicator');
        const prevBtn = root.querySelector('#carousel-prev');
        const nextBtn = root.querySelector('#carousel-next');
        let index = 0;

        function render() {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });

            indicators.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('w-2', 'bg-white/50', 'hover:bg-white/75');
                    dot.classList.add('w-6', 'bg-white');
                } else {
                    dot.classList.remove('w-6', 'bg-white');
                    dot.classList.add('w-2', 'bg-white/50', 'hover:bg-white/75');
                }
            });
        }

        prevBtn?.addEventListener('click', () => {
            index = index === 0 ? slides.length - 1 : index - 1;
            render();
        });

        nextBtn?.addEventListener('click', () => {
            index = index === slides.length - 1 ? 0 : index + 1;
            render();
        });

        indicators.forEach((dot) => {
            dot.addEventListener('click', () => {
                index = Number(dot.dataset.slide);
                render();
            });
        });

        render();
    })();
</script>
