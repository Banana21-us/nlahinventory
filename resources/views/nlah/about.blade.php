@include('partials.head')
<livewire:navigation/>
<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">

    <div class="mt-1">
        <h2 class="text-2xl md:text-3xl font-semibold uppercase tracking-wider mb-6">Who we are</h2>

        <h3 class="text-xl md:text-2xl font-medium mb-5 tracking-tight">Northern Luzon Adventist Hospital</h3>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <div class="lg:w-[40%]">
                <div class="relative pl-6 md:pl-8">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#b43b3b]"></div>
                    
                    <div class="space-y-6 leading-relaxed">
                        <p class="text-base md:text-lg tracking-tight">
                            Northern Luzon Adventist Hospital is a non-stock, non profit healthcare Institution jointly managed
                            and operated by North Philippine Union Conference of the Seventh-Day Adventistthrough Adventist Medical
                            Center Manila Manila
                        </p>
                        <p class="text-base md:text-lg tracking-tight">
                            It is licensed by the Department of Health (DOH) and accredited by the Philippine Hospital Association (PHA),
                            Philippine Health Insurance Corporation(PhilHealth), and various HMOs.
                        </p>
                        <p class="text-base md:text-lg italic tracking-tight">
                            Presently, Northern Luzon Adventist Hospital is a level 1 healthcare facility. Structurally and
                            Department-wise, however, Northern Luzon Adventist Hospital qualifies as a Level 2 facility due
                            to the availability of services being rendered by different specialties such as General and Orthopedic
                            Surgery, Ophthalmology, Otorhinolaryngology, Internal Medicine, Obstetrics & Gynecology and Pediatrics.
                            <br><br>              
                            Northern Luzon Adventist Hospital is working towards its full level 2 status by upgrading its Medical
                            and Nursing services to include a Dialysis Section (Peritoneal and Hemodialysis), Intensive Care
                            Unit/Coronary Care Unit (ICU/CCU), Neonatal Intensive Care Unit (NICU) and Physical Therapy and
                            Rehabilitation Department in the near future.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:w-[60%] flex flex-col gap-4">
                <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md">
                    <img src="/image/areal1.jpg" alt="NLAH" class="w-full h-80 md:h-[400px] object-cover">
                    <div class="p-4 bg-white">
                        <p class="text-sm text-gray-600">Northern Luzon Adventist Hospital</p>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="md:w-1/2 bg-gray-100 rounded-lg overflow-hidden shadow-md">
                        <img src="/image/areal2.jpg" alt="NLAH Facility" class="w-full h-48 md:h-64 object-cover">
                        <div class="p-4 bg-white">
                            <p class="text-sm text-gray-600">Description</p>
                        </div>
                    </div>
                    <div class="md:w-1/2 bg-gray-100 rounded-lg overflow-hidden shadow-md">
                        <img src="/image/services.jpg" alt="NLAH Services" class="w-full h-48 md:h-64 object-cover">
                        <div class="p-4 bg-white">
                            <p class="text-sm text-gray-600">Description</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-100 mt-20 rounded-2xl py-16 px-6 md:px-12 relative overflow-hidden">
        <div class="relative z-10 max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-1xl font-bold text-green-600">ABOUT US</h1>
                <h4 class="text-2xl uppercase tracking-widest text-gray-500 mb-3 font-bold">Mission & Vision</h4>
                <!-- <p class="text-lg text-gray-600 max-w-2xl mx-auto">Delivering compassionate, innovative care with a focus on transforming healthcare and empowering healthier communities.</p> -->
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#b43b3b]">
                    <h3 class="text-3xl font-bold text-gray-800 mb-4 text-center">MISSION</h3>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Northern Luzon Adventist Hospital shall provide
                        the highest standard of healthcare, community service, quality
                        education and training to fulfill the work of the Great Physician
                        in which the Seventh-Day Adventist Church is committed to.
                    </p>
                </div>
                
                <!-- Vision Card -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-[#b43b3b]">
                    <h3 class="text-3xl font-bold text-gray-800 mb-4 text-center">VISION</h3>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        To be a Center if Excellence in healthcare and education
                        ministries dedicated in sharing God's Love.
                </div>
            </div>
        </div>
    </div>

    <div class="mt-20 tracking-normal">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <!-- Left side - Google Maps (60%) -->
            <div class="lg:w-[60%]">
                <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md h-full">
                    <div class="w-full h-100 md:h-[450px] lg:h-[500px]">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3829.623123863821!2d120.5126166759043!3d16.193358484497672!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339172a546ef277f%3A0x279c2703d225a1ed!2sNorthern%20Luzon%20Adventist%20Hospital!5e0!3m2!1sen!2sph!4v1715634567890!5m2!1sen!2sph" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full h-full">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Right side - Contact Information (40%) styled like reference image -->
            <div class="lg:w-[40%]">
                <div class="bg-white p-8 md:p-10 rounded-lg shadow-md h-full flex flex-col justify-center">
                    <h2 class="text-1xl font-bold text-green-600 mb-2 text-center">GET IN TOUCH</h2>
                    
                    <h3 class="text-xl md:text-2xl font-medium text-gray-800 mb-4 text-center">Northern Luzon Adventist Hospital</h3>
                    
                    <p class="text-gray-600 mb-8 text-sm text-center tracking-tight">
                        We're here to help! Reach out to us for inquiries, appointments, or assistance, and experience our commitment to your care.
                    </p>
                    
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex">
                            <div class="min-w-[80px] font-semibold text-gray-700">Address:</div>
                            <div class="text-gray-600">MacArthur Highway, Artacho, Sison, Philippines, 2434</div>
                        </div>
                        
                        <!-- Contact No -->
                        <div class="flex">
                            <div class="min-w-[80px] font-semibold text-gray-700">Contact No.</div>
                            <div class="text-gray-600">(075)-632-3200</div>
                        </div>
                        
                        <!-- Email Address -->
                        <div class="flex">
                            <div class="min-w-[80px] font-semibold text-gray-700">Email Address: </div>
                            <div class="text-blue-800 underline">nlahospital@adventisthealth-pan.com</div>
                        </div>
                    </div>
                    
                    <!-- Contact Us Button -->
                    <div class="mt-8">
                        <a href="#" class="inline-block bg-green-500 hover:bg-green-700 text-white font-medium py-3 px-8 rounded-md transition duration-300 mt-5">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<livewire:footer/>