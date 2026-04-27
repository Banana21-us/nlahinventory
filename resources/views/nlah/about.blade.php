@include('partials.head')
<livewire:navigation/>

<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20 space-y-24">

  {{-- ── Hero / Who We Are ── --}}
  <section>
    <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] text-zinc-900 mb-4">
      Who We Are
    </h1>
    <p class="text-lg md:text-xl text-zinc-500 font-medium max-w-xl leading-snug mb-10">
      Northern Luzon Adventist Hospital
    </p>
    <div class="w-full border-t border-dashed border-zinc-300 mb-12"></div>

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-start">
      {{-- Text --}}
      <div class="lg:w-[42%] space-y-5 text-zinc-600 leading-relaxed text-base">
        <p>
          Northern Luzon Adventist Hospital is a non-stock, non-profit healthcare institution
          jointly managed and operated by North Philippine Union Conference of the Seventh-Day
          Adventist through Adventist Medical Center Manila.
        </p>
        <p>
          It is licensed by the Department of Health (DOH) and accredited by the Philippine
          Hospital Association (PHA), Philippine Health Insurance Corporation (PhilHealth), and
          various HMOs.
        </p>
        <p class="text-zinc-500 text-sm italic">
          Presently a Level 1 healthcare facility, NLAH qualifies structurally as Level 2 due to
          available specialties including General and Orthopedic Surgery, Ophthalmology,
          Otorhinolaryngology, Internal Medicine, Obstetrics &amp; Gynecology, and Pediatrics.
          The hospital is actively working toward full Level 2 status by adding Dialysis, ICU/CCU,
          NICU, and Physical Therapy services.
        </p>
      </div>

      {{-- Photos --}}
      <div class="lg:w-[58%] flex flex-col gap-4">
        <div class="rounded-xl overflow-hidden border border-zinc-200">
          <img src="/image/areal1.jpg" alt="NLAH Aerial View" class="w-full h-72 md:h-96 object-cover">
          <div class="px-4 py-3 bg-white border-t border-zinc-100">
            <p class="text-xs text-zinc-400">Northern Luzon Adventist Hospital — Artacho, Sison, Pangasinan</p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1 rounded-xl overflow-hidden border border-zinc-200">
            <img src="/image/areal2.jpg" alt="NLAH Facility" class="w-full h-44 object-cover">
          </div>
          <div class="flex-1 rounded-xl overflow-hidden border border-zinc-200">
            <img src="/image/services.jpg" alt="NLAH Services" class="w-full h-44 object-cover">
          </div>
        </div>
      </div>
    </div>
  </section>

  

  {{-- ── Location & Contact ── --}}
  <section>
    <div class="w-full border-t border-dashed border-zinc-300 mb-12"></div>
    <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-zinc-900 mb-10">Find Us</h2>

    <div class="flex flex-col lg:flex-row gap-8 items-stretch">
      {{-- Map --}}
      <div class="lg:w-[60%] rounded-xl overflow-hidden border border-zinc-200 min-h-[380px]">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3829.623123863821!2d120.5126166759043!3d16.193358484497672!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x339172a546ef277f%3A0x279c2703d225a1ed!2sNorthern%20Luzon%20Adventist%20Hospital!5e0!3m2!1sen!2sph!4v1715634567890!5m2!1sen!2sph"
          width="100%" height="100%"
          style="border:0; display:block; min-height:380px;"
          allowfullscreen loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>

      {{-- Contact --}}
      <div class="lg:w-[40%] bg-white border border-zinc-200 rounded-xl p-8 flex flex-col justify-center gap-6">
        <div>
          <h3 class="text-xl font-bold text-zinc-900 tracking-tight mb-1">Get in Touch</h3>
          <p class="text-sm text-zinc-500 leading-snug">
            Reach out for inquiries, appointments, or any assistance you need.
          </p>
        </div>

        <div class="space-y-4 text-sm">
          <div class="flex gap-3">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="text-zinc-600">MacArthur Highway, Artacho, Sison, Pangasinan 2434</span>
          </div>
          <div class="flex gap-3">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span class="text-zinc-600">(075) 632-3200</span>
          </div>
          <div class="flex gap-3">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <a href="mailto:nlahospital@adventisthealth-pan.com" class="text-zinc-900 underline underline-offset-2 hover:text-zinc-600 transition-colors break-all">
              nlahospital@adventisthealth-pan.com
            </a>
          </div>
        </div>

        <a href="https://maps.google.com/?cid=2854199161210118637" target="_blank"
          class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Open in Google Maps
        </a>
      </div>
    </div>
  </section>

</main>
<livewire:footer/>
