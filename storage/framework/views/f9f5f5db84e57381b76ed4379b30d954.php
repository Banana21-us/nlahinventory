<?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navigation', []);

$key = null;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-559279701-0', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
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

<!-- FEEDBACKS SECTION - TESTIMONIAL STYLE -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 mb-20">
    <div class="flex items-center justify-between mb-8">
        <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'xl','level' => '2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xl','level' => '2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
Feedbacks <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
        
        <div class="flex gap-2">
            <button 
                onclick="document.getElementById('testimonials-scroll').scrollBy({ left: -320, behavior: 'smooth' })"
                class="p-2 rounded-3xl border border-gray-500 hover:bg-zinc-100 transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button 
                onclick="document.getElementById('testimonials-scroll').scrollBy({ left: 320, behavior: 'smooth' })"
                class="p-2 rounded-3xl border border-gray-500 hover:bg-zinc-100 transition-colors cursor-pointer"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <?php
        use Illuminate\Support\Facades\DB;
        $feedbacks = DB::table('feedbacks')
        ->orderBy('id', 'desc')
        ->get()
        ->map(function($f) {
            $f->formatted_date = \Carbon\Carbon::parse($f->feedback_date)->format('M d, Y');
            return $f;
        });
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($feedbacks->isEmpty()): ?>
    <div class="w-full text-center py-12 bg-zinc-50 rounded-2xl">
        <?php if (isset($component)) { $__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::subheading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::subheading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>
No feedbacks yet. Be the first to share your experience! <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97)): ?>
<?php $attributes = $__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97; ?>
<?php unset($__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97)): ?>
<?php $component = $__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97; ?>
<?php unset($__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97); ?>
<?php endif; ?>
    </div>
    <?php else: ?>
    <div 
        id="testimonials-scroll"
        class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-6"
        style="scrollbar-width: none; -ms-overflow-style: none;"
    >
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="snap-start shrink-0 w-[320px] sm:w-[380px] bg-white rounded-2xl border border-zinc-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="p-6">
                <!-- Header with name and rating -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <!-- Avatar/Initials -->
                        <div class="w-12 h-12 rounded-full bg-[#e8dec9] flex items-center justify-center text-[#5a4e3a] font-semibold text-lg">
                            <?php echo e(strtoupper(substr($feedback->name, 0, 1))); ?>

                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-zinc-800"><?php echo e($feedback->name); ?></h3>
                            <div class="flex items-center gap-1 mt-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i <= $feedback->rating): ?>
                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-sm text-zinc-500 ml-1">(<?php echo e($feedback->rating); ?>/5)</span>
                            </div>
                        </div>
                    </div>
                    <!-- Date -->
                    <div class="text-xs text-zinc-400">
                        <?php echo e(\Carbon\Carbon::parse($feedback->feedback_date)->format('M d, Y')); ?>

                    </div>
                </div>

                <!-- Comment -->
                <div class="relative">
                    <p class="text-zinc-600 leading-relaxed pl-4 italic">
                        "<?php echo e($feedback->comment); ?>"
                    </p>
                </div>

                <!-- Bottom decoration -->
                <!-- <div class="mt-4 pt-4 border-t border-zinc-100 flex justify-between items-center text-xs text-zinc-400">
                    <span>Verified Patient</span>
                    <span>✓</span>
                </div> -->
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('footer', []);

$key = null;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-559279701-1', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?><?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/nlah/home.blade.php ENDPATH**/ ?>