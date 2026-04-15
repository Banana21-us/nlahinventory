@include('partials.head')
<livewire:navigation/>

<main class="max-w-7xl mx-auto px-6 pt-32 md:pt-48 pb-20">

  {{-- Page header --}}
  <div class="mb-16">
    <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-[1.05] text-zinc-900 mb-4">
      Our Services
    </h1>
    <p class="text-lg md:text-xl text-zinc-500 font-medium max-w-2xl leading-snug">
      Northern Luzon Adventist Hospital provides comprehensive medical care for every stage of life — from prevention to advanced treatment.
    </p>
    <div class="w-full border-t border-dashed border-zinc-300 mt-10"></div>
  </div>

  {{-- Service cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

    @php
    $services = [
      ['name'=>'Imaging',                  'sub'=>'Radiology, Ultrasound, CT-scan',      'icon'=>'/image/mri.png',             'desc'=>'Advanced diagnostic imaging services including X-ray, ultrasound, CT scans, and MRI. Our state-of-the-art imaging department provides accurate diagnostics for better treatment planning.'],
      ['name'=>'Laboratory',               'sub'=>'Clinical lab testing, blood work',     'icon'=>'/image/laboratory.png',      'desc'=>'Full-service clinical laboratory offering comprehensive testing including blood work, urinalysis, microbiology, and pathology. Results available quickly with accurate analysis.'],
      ['name'=>'Pharmacy',                 'sub'=>'24/7 prescription services',           'icon'=>'/image/medicine.png',        'desc'=>'24/7 pharmacy services with a wide range of medications and pharmaceutical supplies. Our licensed pharmacists are available to answer questions and provide guidance.'],
      ['name'=>'Cafeteria',                'sub'=>'Healthy meals and beverages',          'icon'=>'/image/cafeteria.png',       'desc'=>'Nutritious and delicious meals prepared fresh daily. Our cafeteria offers a variety of healthy options for patients, visitors, and staff, with special dietary accommodations available.'],
      ['name'=>'Dental',                   'sub'=>'General dentistry, oral surgery',      'icon'=>'/image/dental-service.png',  'desc'=>'Comprehensive dental care including general dentistry, oral surgery, orthodontics, and preventive care. Our dental team is committed to your oral health.'],
      ['name'=>'Chaplaincy',               'sub'=>'Spiritual care and support',           'icon'=>'/image/pray.png',            'desc'=>'Spiritual care and emotional support for patients and families. Our chaplains provide comfort, prayer, and counseling regardless of faith background.'],
      ['name'=>'Shockwave Therapy',        'sub'=>'Non-invasive pain management',         'icon'=>'/image/thunder.png',         'desc'=>'Advanced non-invasive treatment for chronic pain conditions. Shockwave therapy promotes healing and pain relief for various musculoskeletal issues.'],
      ['name'=>'Ambulance',                'sub'=>'Emergency medical transport',          'icon'=>'/image/ambulance.png',       'desc'=>'24/7 emergency medical transport services. Our fully equipped ambulances and trained paramedics ensure safe and rapid transport during emergencies.'],
      ['name'=>'Cardiology',               'sub'=>'Cardiac care and rehabilitation',      'icon'=>'/image/cardiology.png',      'desc'=>'Comprehensive heart care including diagnostics, treatment, and rehabilitation. Our cardiology department handles everything from routine check-ups to complex cardiac conditions.'],
      ['name'=>'ENT',                      'sub'=>'Ear, nose, and throat care',           'icon'=>'/image/ent.png',             'desc'=>'Specialized care for ear, nose, and throat conditions. From hearing loss to sinus issues, our ENT specialists provide expert diagnosis and treatment.'],
      ['name'=>'Family Medicine',          'sub'=>'Comprehensive family healthcare',      'icon'=>'/image/medical.png',         'desc'=>'Comprehensive healthcare for all ages. Our family medicine practitioners provide preventive care, treatment for acute illnesses, and management of chronic conditions.'],
      ['name'=>'Nephrology',               'sub'=>'Kidney care and dialysis',             'icon'=>'/image/kidneys.png',         'desc'=>'Specialized kidney care including diagnosis and treatment of kidney diseases, hypertension management, and dialysis services for patients with kidney failure.'],
      ['name'=>'Neurology',                'sub'=>'Brain and nervous system',             'icon'=>'/image/neurology.png',       'desc'=>'Expert care for disorders of the brain and nervous system. Our neurologists treat conditions such as headaches, seizures, stroke, and neurodegenerative diseases.'],
      ['name'=>'Obstetrics & Gynecology',  'sub'=>"Women's health and maternity",         'icon'=>'/image/woman.png',           'desc'=>"Comprehensive women's health services including prenatal care, childbirth, postpartum care, and gynecological treatments. Our OB/GYN team supports women at every life stage."],
      ['name'=>'Ophthalmology',            'sub'=>'Eye care and vision services',         'icon'=>'/image/ophthalmology.png',   'desc'=>'Complete eye care services including vision tests, prescription glasses, and treatment for eye diseases such as cataracts, glaucoma, and macular degeneration.'],
      ['name'=>'Pediatrics',               'sub'=>'Child and adolescent care',            'icon'=>'/image/pediatrics.png',      'desc'=>'Specialized healthcare for infants, children, and adolescents. Our pediatricians provide well-child visits, vaccinations, and treatment for childhood illnesses.'],
      ['name'=>'Surgery',                  'sub'=>'Advanced surgical procedures',         'icon'=>'/image/surgery-room.png',    'desc'=>'Advanced surgical services including general surgery, orthopedic surgery, and minimally invasive procedures. Our operating rooms are equipped with modern technology.'],
      ['name'=>'Physician Consultation',   'sub'=>'Expert medical consultations',         'icon'=>'/image/consultation.png',    'desc'=>'Expert medical consultations with our team of specialized physicians. Get professional medical advice, second opinions, and treatment recommendations.'],
      ['name'=>'Nutrition Counseling',     'sub'=>'Dietary guidance and planning',        'icon'=>'/image/food.png',            'desc'=>'Professional dietary guidance from registered nutritionists. We provide personalized meal planning for weight management, medical conditions, and overall wellness.'],
      ['name'=>'Dialysis Center',          'sub'=>'Coming soon',                          'icon'=>'/image/dialysis-machine.png','desc'=>'Our upcoming dialysis center will provide comprehensive kidney dialysis services. Stay tuned for updates on this exciting new facility.'],
    ];
    @endphp

    @foreach ($services as $svc)
    <button
      type="button"
      class="service-card group text-left w-full bg-white border border-zinc-200 rounded-xl p-5 flex items-start gap-4 hover:border-zinc-400 hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2"
      data-name="{{ $svc['name'] }}"
      data-sub="{{ $svc['sub'] }}"
      data-icon="{{ $svc['icon'] }}"
      data-desc="{{ $svc['desc'] }}"
      @if($svc['sub'] === 'Coming soon') data-soon="1" @endif
    >
      <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#e8dec9] flex items-center justify-center group-hover:bg-[#ddd0b8] transition-colors">
        <img src="{{ $svc['icon'] }}" class="w-5 h-5 object-contain" alt="{{ $svc['name'] }}">
      </div>
      <div class="min-w-0">
        <p class="font-semibold text-sm text-zinc-900 leading-tight">{{ $svc['name'] }}</p>
        <p class="text-xs text-zinc-500 mt-0.5 leading-snug">
          @if($svc['sub'] === 'Coming soon')
            <span class="inline-block px-1.5 py-0.5 bg-zinc-100 text-zinc-500 rounded text-[10px] font-medium tracking-wide">Coming soon</span>
          @else
            {{ $svc['sub'] }}
          @endif
        </p>
      </div>
    </button>
    @endforeach

  </div>
</main>

{{-- Modal --}}
<div id="svcModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
  {{-- Backdrop --}}
  <div id="svcOverlay" class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

  {{-- Panel --}}
  <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
    <div
      id="svcPanel"
      class="pointer-events-auto relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden
             scale-95 opacity-0 transition-all duration-200"
    >
      {{-- Header --}}
      <div class="bg-zinc-900 px-6 py-5 flex items-center gap-4">
        <div id="svcModalIconWrap" class="w-11 h-11 rounded-xl bg-[#e8dec9] flex items-center justify-center flex-shrink-0">
          <img id="svcModalIcon" src="" class="w-6 h-6 object-contain" alt="">
        </div>
        <div class="flex-1 min-w-0">
          <h2 id="svcModalTitle" class="text-base font-semibold text-white leading-tight tracking-tight"></h2>
          <p id="svcModalSub" class="text-xs text-zinc-400 mt-0.5"></p>
        </div>
        <button id="svcCloseIcon" type="button" aria-label="Close"
          class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-zinc-400 hover:text-white hover:bg-zinc-700 transition-colors">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>

      {{-- Body --}}
      <div class="px-6 py-5">
        <p id="svcModalDesc" class="text-sm text-zinc-600 leading-relaxed"></p>
      </div>

      {{-- Footer --}}
      <div class="px-6 pb-5">
        <button id="svcCloseBtn" type="button"
          class="w-full py-2.5 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<livewire:footer/>

<script>
(function () {
  const modal    = document.getElementById('svcModal');
  const overlay  = document.getElementById('svcOverlay');
  const panel    = document.getElementById('svcPanel');
  const title    = document.getElementById('svcModalTitle');
  const sub      = document.getElementById('svcModalSub');
  const desc     = document.getElementById('svcModalDesc');
  const icon     = document.getElementById('svcModalIcon');

  function openModal(card) {
    title.textContent = card.dataset.name;
    sub.textContent   = card.dataset.sub === 'Coming soon' ? '— Coming soon' : card.dataset.sub;
    desc.textContent  = card.dataset.desc;
    icon.src          = card.dataset.icon;
    icon.alt          = card.dataset.name;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(() => {
      overlay.classList.add('opacity-100');
      panel.classList.remove('scale-95', 'opacity-0');
      panel.classList.add('scale-100', 'opacity-100');
    });
  }

  function closeModal() {
    overlay.classList.remove('opacity-100');
    panel.classList.remove('scale-100', 'opacity-100');
    panel.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = '';
    }, 200);
  }

  document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('click', () => openModal(card));
  });

  document.getElementById('svcCloseBtn').addEventListener('click', closeModal);
  document.getElementById('svcCloseIcon').addEventListener('click', closeModal);
  overlay.addEventListener('click', closeModal);

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
  });
})();
</script>
