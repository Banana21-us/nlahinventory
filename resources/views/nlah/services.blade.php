@include('partials.head')
<livewire:navigation/>
<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">
    <div class="w-full mx-auto mt-1">
        <div class="text-center mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">OUR SERVICES</h2>
            <p class="max-w-4xl mx-auto">
                <span class="font-semibold">Northern Luzon Adventist Hospital</span> (NLAH) in Sison, Pangasinan, is a Level 1 accredited facility providing 24/7 emergency care, clinical laboratory services, and 18-bed inpatient care, including specialized OB/GYN and operating rooms
            </p>
        </div>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-5">
                <!-- Card 1: IMAGING -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="IMAGING" data-description="Advanced diagnostic imaging services including X-ray, ultrasound, CT scans, and MRI. Our state-of-the-art imaging department provides accurate diagnostics for better treatment planning." data-icon="/image/mri.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/mri.png" class="w-8 h-8" alt="Imaging">
                    </div>
                    <div>
                        <h4 class="font-semibold">IMAGING</h4>
                        <p class="text-sm">Radiology, Ultrasound, CT-scan</p>
                    </div>
                </div>
                
                <!-- Card 2: LABORATORY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="LABORATORY" data-description="Full-service clinical laboratory offering comprehensive testing services including blood work, urinalysis, microbiology, and pathology. Results available quickly with accurate analysis." data-icon="/image/laboratory.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/laboratory.png" class="w-8 h-8" alt="Laboratory">
                    </div>
                    <div>
                        <h4 class="font-semibold">LABORATORY</h4>
                        <p class="text-sm">Clinical lab testing, blood work</p>
                    </div>
                </div>
                
                <!-- Card 3: PHARMACY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="PHARMACY" data-description="24/7 pharmacy services with a wide range of medications and pharmaceutical supplies. Our licensed pharmacists are available to answer questions and provide guidance." data-icon="/image/medicine.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/medicine.png" class="w-8 h-8" alt="Pharmacy">
                    </div>
                    <div>
                        <h4 class="font-semibold">PHARMACY</h4>
                        <p class="text-sm">24/7 prescription services</p>
                    </div>
                </div>
                
                <!-- Card 4: CAFETERIA -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="CAFETERIA" data-description="Nutritious and delicious meals prepared fresh daily. Our cafeteria offers a variety of healthy options for patients, visitors, and staff, with special dietary accommodations available." data-icon="/image/cafeteria.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/cafeteria.png" class="w-8 h-8" alt="Cafeteria">
                    </div>
                    <div>
                        <h4 class="font-semibold">CAFETERIA</h4>
                        <p class="text-sm">Healthy meals and beverages</p>
                    </div>
                </div>
                
                <!-- Card 5: DENTAL -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="DENTAL" data-description="Comprehensive dental care including general dentistry, oral surgery, orthodontics, and preventive care. Our dental team is committed to your oral health." data-icon="/image/dental-service.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/dental-service.png" class="w-8 h-8" alt="Dental">
                    </div>
                    <div>
                        <h4 class="font-semibold">DENTAL</h4>
                        <p class="text-sm">General dentistry, oral surgery</p>
                    </div>
                </div>
                
                <!-- Card 6: CHAPLAINCY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="CHAPLAINCY" data-description="Spiritual care and emotional support for patients and families. Our chaplains provide comfort, prayer, and counseling regardless of faith background." data-icon="/image/pray.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/pray.png" class="w-8 h-8" alt="Chaplaincy">
                    </div>
                    <div>
                        <h4 class="font-semibold">CHAPLAINCY</h4>
                        <p class="text-sm">Spiritual care and support</p>
                    </div>
                </div>
                
                <!-- Card 7: SHOCKWAVE THERAPY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="SHOCKWAVE THERAPY" data-description="Advanced non-invasive treatment for chronic pain conditions. Shockwave therapy promotes healing and pain relief for various musculoskeletal issues." data-icon="/image/thunder.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/thunder.png" class="w-8 h-8" alt="Shockwave Therapy">
                    </div>
                    <div>
                        <h4 class="font-semibold">SHOCKWAVE THERAPY</h4>
                        <p class="text-sm">Non-invasive pain management</p>
                    </div>
                </div>
                
                <!-- Card 8: AMBULANCE -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="AMBULANCE" data-description="24/7 emergency medical transport services. Our fully equipped ambulances and trained paramedics ensure safe and rapid transport during emergencies." data-icon="/image/ambulance.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ambulance.png" class="w-8 h-8" alt="Ambulance">
                    </div>
                    <div>
                        <h4 class="font-semibold">AMBULANCE</h4>
                        <p class="text-sm">Emergency medical transport</p>
                    </div>
                </div>
                
                <!-- Card 9: CARDIOLOGY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="CARDIOLOGY" data-description="Comprehensive heart care including diagnostics, treatment, and rehabilitation. Our cardiology department handles everything from routine check-ups to complex cardiac conditions." data-icon="/image/cardiology.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/cardiology.png" class="w-8 h-8" alt="Cardiology">
                    </div>
                    <div>
                        <h4 class="font-semibold">CARDIOLOGY</h4>
                        <p class="text-sm">Cardiac care and rehabilitation</p>
                    </div>
                </div>
                
                <!-- Card 10: ENT -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="ENT" data-description="Specialized care for ear, nose, and throat conditions. From hearing loss to sinus issues, our ENT specialists provide expert diagnosis and treatment." data-icon="/image/ent.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ent.png" class="w-8 h-8" alt="ENT">
                    </div>
                    <div>
                        <h4 class="font-semibold">ENT</h4>
                        <p class="text-sm">Ear, nose, and throat care</p>
                    </div>
                </div>
                
                <!-- Card 11: FAMILY MEDICINE -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="FAMILY MEDICINE" data-description="Comprehensive healthcare for all ages. Our family medicine practitioners provide preventive care, treatment for acute illnesses, and management of chronic conditions." data-icon="/image/medical.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/medical.png" class="w-8 h-8" alt="Family Medicine">
                    </div>
                    <div>
                        <h4 class="font-semibold">FAMILY MEDICINE</h4>
                        <p class="text-sm">Comprehensive family healthcare</p>
                    </div>
                </div>
                
                <!-- Card 12: NEPHROLOGY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="NEPHROLOGY" data-description="Specialized kidney care including diagnosis and treatment of kidney diseases, hypertension management, and dialysis services for patients with kidney failure." data-icon="/image/kidneys.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/kidneys.png" class="w-8 h-8" alt="Nephrology">
                    </div>
                    <div>
                        <h4 class="font-semibold">NEPHROLOGY</h4>
                        <p class="text-sm">Kidney care and dialysis</p>
                    </div>
                </div>
                
                <!-- Card 13: NEUROLOGY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="NEUROLOGY" data-description="Expert care for disorders of the brain and nervous system. Our neurologists treat conditions such as headaches, seizures, stroke, and neurodegenerative diseases." data-icon="/image/neurology.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/neurology.png" class="w-8 h-8" alt="Neurology">
                    </div>
                    <div>
                        <h4 class="font-semibold">NEUROLOGY</h4>
                        <p class="text-sm">Brain and nervous system</p>
                    </div>
                </div>
                
                <!-- Card 14: OBSTETRICS & GYNECOLOGY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="OBSTETRICS & GYNECOLOGY" data-description="Comprehensive women's health services including prenatal care, childbirth, postpartum care, and gynecological treatments. Our OB/GYN team supports women at every life stage." data-icon="/image/woman.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/woman.png" class="w-8 h-8" alt="OB/GYN">
                    </div>
                    <div>
                        <h4 class="font-semibold">OBSTETRICS & GYNECOLOGY</h4>
                        <p class="text-sm">Women's health and maternity</p>
                    </div>
                </div>
                
                <!-- Card 15: OPHTHALMOLOGY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="OPHTHALMOLOGY" data-description="Complete eye care services including vision tests, prescription glasses, and treatment for eye diseases such as cataracts, glaucoma, and macular degeneration." data-icon="/image/ophthalmology.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ophthalmology.png" class="w-8 h-8" alt="Ophthalmology">
                    </div>
                    <div>
                        <h4 class="font-semibold">OPHTHALMOLOGY</h4>
                        <p class="text-sm">Eye care and vision services</p>
                    </div>
                </div>
                
                <!-- Card 16: PEDIATRICS -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="PEDIATRICS" data-description="Specialized healthcare for infants, children, and adolescents. Our pediatricians provide well-child visits, vaccinations, and treatment for childhood illnesses." data-icon="/image/pediatrics.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/pediatrics.png" class="w-8 h-8" alt="Pediatrics">
                    </div>
                    <div>
                        <h4 class="font-semibold">PEDIATRICS</h4>
                        <p class="text-sm">Child and adolescent care</p>
                    </div>
                </div>
                
                <!-- Card 17: SURGERY -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="SURGERY" data-description="Advanced surgical services including general surgery, orthopedic surgery, and minimally invasive procedures. Our operating rooms are equipped with modern technology." data-icon="/image/surgery-room.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/surgery-room.png" class="w-8 h-8" alt="Surgery">
                    </div>
                    <div>
                        <h4 class="font-semibold">SURGERY</h4>
                        <p class="text-sm">Advanced surgical procedures</p>
                    </div>
                </div>
                
                <!-- Card 18: PHYSICIAN CONSULTATION -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="PHYSICIAN CONSULTATION" data-description="Expert medical consultations with our team of specialized physicians. Get professional medical advice, second opinions, and treatment recommendations." data-icon="/image/consultation.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/consultation.png" class="w-8 h-8" alt="Consultation">
                    </div>
                    <div>
                        <h4 class="font-semibold">PHYSICIAN CONSULTATION</h4>
                        <p class="text-sm">Expert medical consultations</p>
                    </div>
                </div>
                
                <!-- Card 19: NUTRITION COUNSELING -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="NUTRITION COUNSELING" data-description="Professional dietary guidance from registered nutritionists. We provide personalized meal planning for weight management, medical conditions, and overall wellness." data-icon="/image/food.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/food.png" class="w-8 h-8" alt="Nutrition">
                    </div>
                    <div>
                        <h4 class="font-semibold">NUTRITION COUNSELING</h4>
                        <p class="text-sm">Dietary guidance and planning</p>
                    </div>
                </div>
                
                <!-- Card 20: DIALYSIS CENTER -->
                <div class="service-card p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6 cursor-pointer" data-service="DIALYSIS CENTER" data-description="Our upcoming dialysis center will provide comprehensive kidney dialysis services. Stay tuned for updates on this new facility." data-icon="/image/dialysis-machine.png">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/dialysis-machine.png" class="w-8 h-8" alt="Dialysis">
                    </div>
                    <div>
                        <h4 class="font-semibold">DIALYSIS CENTER</h4>
                        <p class="text-sm">Coming sooooon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Details Modal -->
    <div id="serviceModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

        <div id="modalContainer" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0 transition-all duration-300">
                    
                    <!-- Close button (top right) -->
                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                        <button id="closeModalIconBtn" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal content -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <!-- Icon container -->
                            <div class="mx-auto flex h-12 w-20 flex-shrink-0 items-center justify-center rounded-full bg-gray-500 sm:mx-0 sm:h-10 sm:w-10">
                                <img id="modalIcon" src="" class="h-10 w-12 hidden" alt="Service icon">
                                <span id="modalIconFont" class="text-3xl text-cyan-600 hidden"></span>
                            </div>
                            
                            <!-- Text content -->
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 bg-teal-700" id="modalTitle">Service Title</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" id="modalDescription">Service description will appear here.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer with close button -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button id="closeModalBtn" type="button" class="inline-flex w-full justify-center rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-cyan-500 sm:ml-3 sm:w-auto">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<livewire:footer/>

<style>
/* Reserve scrollbar space to prevent horizontal layout shift when modal opens */
html {
    scrollbar-gutter: stable;
}

/* Ensure backdrop blur works properly */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Ensure modal is visible */
#serviceModal {
    display: none;
}

#serviceModal:not(.hidden) {
    display: block;
}

/* Prevent body scroll without shifting content */
body.modal-open {
    overflow: hidden;
}

/* Remove any margin shifts */
main {
    transition: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all service cards
    const serviceCards = document.querySelectorAll('.service-card');
    
    // Get modal elements
    const modal = document.getElementById('serviceModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalIcon = document.getElementById('modalIcon');
    const modalIconFont = document.getElementById('modalIconFont');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModalIconBtn = document.getElementById('closeModalIconBtn');
    const overlay = document.getElementById('overlay');
    const modalContainer = document.getElementById('modalContainer');
    const modalPanel = document.getElementById('modalPanel');

    // Check if modal exists before proceeding
    if (!modal) {
        console.error('Modal element not found');
        return;
    }

    // Open modal when service card is clicked
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            const service = this.dataset.service;
            const description = this.dataset.description;
            const icon = this.dataset.icon;
            
            if (modalTitle) modalTitle.textContent = service;
            if (modalDescription) modalDescription.textContent = description;
            
            // Handle icon display
            if (icon.startsWith('fas')) {
                if (modalIcon) modalIcon.classList.add('hidden');
                if (modalIconFont) {
                    modalIconFont.className = icon + ' text-3xl text-blue-600';
                    modalIconFont.classList.remove('hidden');
                }
            } else {
                if (modalIconFont) modalIconFont.classList.add('hidden');
                if (modalIcon) {
                    modalIcon.src = icon;
                    modalIcon.classList.remove('hidden');
                }
            }
            
            // Show modal with animation
            modal.classList.remove('hidden');
            
            // Force reflow to ensure transition works
            void modal.offsetWidth;
            
            // Trigger animations
            if (overlay) overlay.classList.add('opacity-100');
            if (modalPanel) {
                modalPanel.classList.remove('scale-95', 'opacity-0');
                modalPanel.classList.add('scale-100', 'opacity-100');
            }
            
            // Add class to body to prevent scrolling
            document.body.classList.add('modal-open');
        });
    });

    // Close modal function
    function closeModal() {
        // Reverse animations
        if (overlay) overlay.classList.remove('opacity-100');
        if (modalPanel) {
            modalPanel.classList.remove('scale-100', 'opacity-100');
            modalPanel.classList.add('scale-95', 'opacity-0');
        }
        
        // Hide modal after animation completes
        setTimeout(() => {
            if (modal) modal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        }, 300);
    }

    // Close modal when close button is clicked
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    if (closeModalIconBtn) {
        closeModalIconBtn.addEventListener('click', closeModal);
    }

    // Close modal when clicking on the background overlay
    if (overlay) {
        overlay.addEventListener('click', closeModal);
    }

    // Close modal when clicking on the modal container
    if (modalContainer) {
        modalContainer.addEventListener('click', function(e) {
            if (e.target === modalContainer) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>