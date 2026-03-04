@include('partials.head')
<livewire:navigation/>
<main class="max-w-7xl mx-auto px-4 sm:px-6 pt-28 sm:pt-32 md:pt-48 pb-16 sm:pb-20">
    <div class="w-full mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">OUR SERVICES</h2>
            <p class="max-w-4xl mx-auto">
                <span class="font-semibold">Northern Luzon Adventist Hospital</span> (NLAH) in Sison, Pangasinan, is a Level 1 accredited facility providing 24/7 emergency care, clinical laboratory services, and 18-bed inpatient care, including specialized OB/GYN and operating rooms
            </p>
        </div>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-5">
                <!-- Card 1: IMAGING -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/mri.png" class="w-8 h-8" alt="Imaging">
                    </div>
                    <div>
                        <h4 class="font-semibold">IMAGING</h4>
                        <p class="text-sm">Radiology, Ultrasound, CT-scan</p>
                    </div>
                </div>
                
                <!-- Card 2: LABORATORY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/laboratory.png" class="w-8 h-8" alt="Laboratory">
                    </div>
                    <div>
                        <h4 class="font-semibold">LABORATORY</h4>
                        <p class="text-sm">Clinical lab testing, blood work</p>
                    </div>
                </div>
                
                <!-- Card 3: PHARMACY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/medicine.png" class="w-8 h-8" alt="Pharmacy">
                    </div>
                    <div>
                        <h4 class="font-semibold">PHARMACY</h4>
                        <p class="text-sm">24/7 prescription services</p>
                    </div>
                </div>
                
                <!-- Card 4: CAFETERIA -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/cafeteria.png" class="w-8 h-8" alt="Cafeteria">
                    </div>
                    <div>
                        <h4 class="font-semibold">CAFETERIA</h4>
                        <p class="text-sm">Healthy meals and beverages</p>
                    </div>
                </div>
                
                <!-- Card 5: DENTAL -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/dental-service.png" class="w-8 h-8" alt="Dental">
                    </div>
                    <div>
                        <h4 class="font-semibold">DENTAL</h4>
                        <p class="text-sm">General dentistry, oral surgery</p>
                    </div>
                </div>
                
                <!-- Card 6: CHAPLAINCY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/pray.png" class="w-8 h-8" alt="Chaplaincy">
                    </div>
                    <div>
                        <h4 class="font-semibold">CHAPLAINCY</h4>
                        <p class="text-sm">Spiritual care and support</p>
                    </div>
                </div>
                
                <!-- Card 7: SHOCKWAVE THERAPY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <i class="fas fa-bolt text-2xl text-gray-700"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold">SHOCKWAVE THERAPY</h4>
                        <p class="text-sm">Non-invasive pain management</p>
                    </div>
                </div>
                
                <!-- Card 8: AMBULANCE -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ambulance.png" class="w-8 h-8" alt="Ambulance">
                    </div>
                    <div>
                        <h4 class="font-semibold">AMBULANCE</h4>
                        <p class="text-sm">Emergency medical transport</p>
                    </div>
                </div>
                
                <!-- Card 9: CARDIOLOGY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/cardiology.png" class="w-8 h-8" alt="Cardiology">
                    </div>
                    <div>
                        <h4 class="font-semibold">CARDIOLOGY</h4>
                        <p class="text-sm">Cardiac care and rehabilitation</p>
                    </div>
                </div>
                
                <!-- Card 10: ENT -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ent.png" class="w-8 h-8" alt="ENT">
                    </div>
                    <div>
                        <h4 class="font-semibold">ENT</h4>
                        <p class="text-sm">Ear, nose, and throat care</p>
                    </div>
                </div>
                
                <!-- Card 11: FAMILY MEDICINE -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/medical.png" class="w-8 h-8" alt="Family Medicine">
                    </div>
                    <div>
                        <h4 class="font-semibold">FAMILY MEDICINE</h4>
                        <p class="text-sm">Comprehensive family healthcare</p>
                    </div>
                </div>
                
                <!-- Card 12: NEPHROLOGY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/kidneys.png" class="w-8 h-8" alt="Nephrology">
                    </div>
                    <div>
                        <h4 class="font-semibold">NEPHROLOGY</h4>
                        <p class="text-sm">Kidney care and dialysis</p>
                    </div>
                </div>
                
                <!-- Card 13: NEUROLOGY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/neurology.png" class="w-8 h-8" alt="Neurology">
                    </div>
                    <div>
                        <h4 class="font-semibold">NEUROLOGY</h4>
                        <p class="text-sm">Brain and nervous system</p>
                    </div>
                </div>
                
                <!-- Card 14: OBSTETRICS & GYNECOLOGY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/woman.png" class="w-8 h-8" alt="OB/GYN">
                    </div>
                    <div>
                        <h4 class="font-semibold">OBSTETRICS & GYNECOLOGY</h4>
                        <p class="text-sm">Women's health and maternity</p>
                    </div>
                </div>
                
                <!-- Card 15: OPHTHALMOLOGY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/ophthalmology.png" class="w-8 h-8" alt="Ophthalmology">
                    </div>
                    <div>
                        <h4 class="font-semibold">OPHTHALMOLOGY</h4>
                        <p class="text-sm">Eye care and vision services</p>
                    </div>
                </div>
                
                <!-- Card 16: PEDIATRICS -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/pediatrics.png" class="w-8 h-8" alt="Pediatrics">
                    </div>
                    <div>
                        <h4 class="font-semibold">PEDIATRICS</h4>
                        <p class="text-sm">Child and adolescent care</p>
                    </div>
                </div>
                
                <!-- Card 17: SURGERY -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/surgery-room.png" class="w-8 h-8" alt="Surgery">
                    </div>
                    <div>
                        <h4 class="font-semibold">SURGERY</h4>
                        <p class="text-sm">Advanced surgical procedures</p>
                    </div>
                </div>
                
                <!-- Card 18: PHYSICIAN CONSULTATION -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/consultation.png" class="w-8 h-8" alt="Consultation">
                    </div>
                    <div>
                        <h4 class="font-semibold">PHYSICIAN CONSULTATION</h4>
                        <p class="text-sm">Expert medical consultations</p>
                    </div>
                </div>
                
                <!-- Card 19: NUTRITION COUNSELING -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/food.png" class="w-8 h-8" alt="Nutrition">
                    </div>
                    <div>
                        <h4 class="font-semibold">NUTRITION COUNSELING</h4>
                        <p class="text-sm">Dietary guidance and planning</p>
                    </div>
                </div>
                
                <!-- Card 20: DIALYSIS CENTER -->
                <div class="p-4 border border-gray-300 rounded-lg hover:shadow-lg transition-shadow duration-300 flex items-start gap-4 sm:gap-6">
                    <div class="flex-shrink-0 bg-gray-100 p-3 rounded-full">
                        <img src="/image/dialysis-machine.png" class="w-8 h-8" alt="Dialysis">
                    </div>
                    <div>
                        <h4 class="font-semibold">DIALYSIS CENTER</h4>
                        <p class="text-sm">Coming soon</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<livewire:footer/>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceItems = document.querySelectorAll('.grid > div');
    const counterElement = document.getElementById('service-counter');
    if (counterElement) {
        counterElement.textContent = `${serviceItems.length} medical services available`;
    }
});
</script>
