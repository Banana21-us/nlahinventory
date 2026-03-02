<?php

use function Livewire\Volt\{state};

$currentYear = date('Y');

?>

<div class="relative w-full min-h-[400px] overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat z-0" style="background-image: url('/image/areal1.jpg');"></div>

    <!-- Color overlay with opacity so image remains visible -->
    <div class="absolute inset-0 bg-teal-900/70 z-10"></div>

    <footer class="relative text-white py-8 sm:py-10 md:py-12 px-4 md:px-8 z-20">
        <div class="container mx-auto max-w-7xl">
            <!-- Main grid: 1 col on mobile, 2 on tablet, 4 on desktop -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- Column 1: Logo, Description, Social, Desktop Back to Top -->
                <div class="text-center sm:text-left w-full">
                    <div class="flex items-center justify-center sm:justify-start gap-3 mb-4 w-full">
                        <img
                            src="/image/logo.png"
                            alt="Northern Luzon Adventist Hospital logo"
                            class="h-20 w-20 rounded-full object-cover border border-white/30"
                        >
                        <h2 class="text-xl sm:text-2xl font-bold">NORTHERN LUZON ADVENTIST HOSPITAL</h2>
                    </div>
                    <p class="text-gray-200 text-xs sm:text-sm leading-relaxed max-w-md mx-auto sm:mx-0">
                        Empowering physicians with advanced multi-modal tools to improve treatment selection and patient outcomes.
                    </p>
                    
                    <!-- Social Icons -->
                    <div class="flex justify-center sm:justify-start space-x-4 mt-5">
                        <a href="#" class="text-gray-200 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-lg sm:text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-200 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-lg sm:text-xl"></i>
                        </a>
                        <a href="https://www.facebook.com/nlahospitalinc" class="text-gray-200 hover:text-white transition-colors">
                            <i class="fab fa-facebook text-lg sm:text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-200 hover:text-white transition-colors">
                            <i class="fab fa-youtube text-lg sm:text-xl"></i>
                        </a>
                    </div>
                    
                    <!-- Desktop Back to Top Button -->
                    <button 
                        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                        class="hidden lg:flex items-center justify-center sm:justify-start space-x-2 text-gray-200 hover:text-white transition-colors group border border-white/30 hover:border-white px-4 py-2 rounded-md mt-6 w-full sm:w-auto"
                    >
                        <span class="text-sm">BACK TO TOP</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    </button>
                </div>
                
                <!-- Column 2: Site Map -->
                <div class="text-center sm:text-left lg:col-start-3">
                    <h3 class="text-base sm:text-lg font-semibold mb-4 text-white">Medical Department</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Medicine</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Surgery</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Pediatrics</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Obstetrics / Gynecology</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Family Medicine</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">ENT (Ear, Nose, and Throat)</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Orthopedic</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Dental</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Ophthalmology</a></li>
                    </ul>
                </div>
                
                <!-- Column 3: Legal -->
                <div class="text-center sm:text-left lg:col-start-4">
                    <h3 class="text-base sm:text-lg font-semibold mb-4 text-white">Latest News</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.youtube.com/watch?v=-Ly9T6tWw6o" class="text-gray-200 hover:text-white transition-colors text-sm">Healthy Pangasinan</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Terms of Services</a></li>
                        <li><a href="#" class="text-gray-200 hover:text-white transition-colors text-sm">Lawyer's Corners</a></li>
                    </ul>
                </div>
                
                <!-- Removed empty spacer column -->
            </div>
            
            <!-- Bottom Bar with Copyright and Mobile Back to Top -->
            <div class="border-t border-green-700/50 pt-6 mt-10 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-gray-300 text-xs sm:text-sm text-center sm:text-left px-2">
                    © Northern Luzon Adventist Hospital Inc. All rights reserved.
                </p>
                
                <!-- Mobile Back to Top Button (hidden on desktop) -->
                <button 
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                    class="lg:hidden flex items-center justify-center space-x-2 text-gray-200 hover:text-white transition-colors group border border-white/30 hover:border-white px-4 py-2 rounded-md text-sm w-full sm:w-auto"
                >
                    <span>BACK TO TOP</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>
            </div>
        </div>
    </footer>
</div>
