<div class="min-h-screen font-sans nlah-page-bg" style="background:#F4F6F9;">
<style>
    .brand-primary  { color:#015581; }
    .brand-bg-p     { background:#015581; }
    .brand-bg-p-lt  { background:#e6f0f7; }
    .brand-accent   { color:#f0b626; }
    .brand-bg-a     { background:#f0b626; }
    .brand-bg-a-lt  { background:#fef8e7; }
    .brand-teal     { color:#027c8b; }
    .brand-bg-t     { background:#027c8b; }
    .brand-bg-t-lt  { background:#e6f4f5; }
    .s-card         { background:#fff; border-radius:14px; border:1px solid #e5e7eb; }
    .s-head         { padding:18px 24px; border-bottom:1px solid #f3f4f6; }
    .s-body         { padding:20px 24px; }
    .kpi-icon       { width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
    .gauge-track    { background:#e5e7eb; border-radius:99px; height:10px; overflow:hidden; }
    .gauge-fill     { height:10px; border-radius:99px; transition:width .5s ease; }
    .contrib-row    { display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-bottom:1px solid #f3f4f6; }
    .contrib-row:last-child { border:0; }
    .badge          { display:inline-flex;align-items:center;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:700; }
</style>

    
    <div class="px-8 pt-8 pb-0 flex items-end justify-between">
        <div>
            <p class="text-[10px] font-black tracking-[0.35em] uppercase brand-accent mb-1">Human Resources</p>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-none">Payroll &amp; Labor Compliance</h1>
            <p class="text-sm text-gray-400 font-medium mt-1">Philippine Labor Standards · DOLE</p>
        </div>
        <div class="text-right text-xs text-gray-400">
            <p class="font-bold text-gray-600"><?php echo e(now()->format('F Y')); ?></p>
            <p><?php echo e(now()->format('l, d M Y')); ?></p>
        </div>
    </div>

    <div class="mx-8 mt-4 mb-6 h-px" style="background:linear-gradient(to right,#015581,#027c8b,#f0b62620,transparent);"></div>

    <div class="px-8 pb-10 flex flex-col gap-6">

        
        <div class="s-card overflow-hidden">
            <div class="s-head flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">DOLE · Night Work Act</p>
                    <h2 class="text-lg font-black text-gray-800 mt-0.5">Shift Differential Calculator</h2>
                </div>
                <span class="badge brand-bg-p-lt brand-primary">Night shift 7 PM – 7 AM</span>
            </div>

            <div class="s-body">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Shift Date</label>
                        <input type="date" wire:model.live="shiftDate"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-[#015581] focus:ring-2 focus:ring-[#015581]/20"/>
                    </div>

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Clock In</label>
                        <input type="time" wire:model.live="shiftIn"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-[#015581] focus:ring-2 focus:ring-[#015581]/20"/>
                    </div>

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Clock Out</label>
                        <input type="time" wire:model.live="shiftOut"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-[#015581] focus:ring-2 focus:ring-[#015581]/20"/>
                    </div>

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Day Type</label>
                        <select wire:model.live="shiftDayType"
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm bg-white focus:outline-none focus:border-[#015581] focus:ring-2 focus:ring-[#015581]/20">
                            <option value="regular">Ordinary Day</option>
                            <option value="special">Special Non-Working / Rest Day</option>
                            <option value="regular_holiday">Regular Holiday</option>
                        </select>
                    </div>

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Hourly Rate (₱)</label>
                        <input type="number" wire:model.live="hourlyRate" min="0" step="0.01" placeholder="0.00"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-[#015581] focus:ring-2 focus:ring-[#015581]/20"/>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    
                    <div class="rounded-xl brand-bg-p-lt border border-blue-100 p-4 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest brand-primary mb-1">Regular Hours</p>
                        <p class="text-2xl font-black text-gray-900"><?php echo e(number_format($regularHours, 1)); ?></p>
                        <p class="text-[11px] text-gray-400 mt-0.5">×1.00 base rate</p>
                    </div>

                    
                    <div class="rounded-xl brand-bg-t-lt border border-teal-100 p-4 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest brand-teal mb-1">Night Diff Hours</p>
                        <p class="text-2xl font-black text-gray-900"><?php echo e(number_format($nightDiffHours, 1)); ?></p>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shiftDayType === 'regular'): ?> ×1.10 premium
                            <?php elseif($shiftDayType === 'special'): ?> ×1.375 premium
                            <?php else: ?> ×2.10 premium
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    
                    <div class="rounded-xl brand-bg-a-lt border border-yellow-100 p-4 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest brand-accent mb-1">Overtime Hours</p>
                        <p class="text-2xl font-black text-gray-900"><?php echo e(number_format($overtimeHours, 1)); ?></p>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shiftDayType === 'regular'): ?> ×1.25 OT
                            <?php elseif($shiftDayType === 'special'): ?> ×1.69 OT
                            <?php else: ?> ×2.60 OT
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    
                    <div class="rounded-xl border p-4 text-center <?php echo e($grossPay > 0 ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-700 mb-1">Estimated Gross</p>
                        <p class="text-2xl font-black text-gray-900">₱<?php echo e(number_format($grossPay, 2)); ?></p>
                        <p class="text-[11px] text-gray-400 mt-0.5">for this shift</p>
                    </div>
                </div>

                
                <div class="mt-5 rounded-xl bg-gray-50 border border-gray-100 px-5 py-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">DOLE Multiplier Reference</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                        <div class="space-y-1">
                            <p class="font-black text-gray-600 uppercase tracking-wide">Ordinary Day</p>
                            <p class="text-gray-500">Regular: ×1.00 &nbsp;|&nbsp; Night Diff: ×1.10</p>
                            <p class="text-gray-500">OT: ×1.25 &nbsp;|&nbsp; OT + ND: ×1.375</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-black text-gray-600 uppercase tracking-wide">Special Non-Working / Rest Day</p>
                            <p class="text-gray-500">Regular: ×1.30 &nbsp;|&nbsp; Night Diff: ×1.375</p>
                            <p class="text-gray-500">OT: ×1.69 &nbsp;|&nbsp; OT + ND: ×1.859</p>
                        </div>
                        <div class="space-y-1">
                            <p class="font-black text-gray-600 uppercase tracking-wide">Regular Holiday</p>
                            <p class="text-gray-500">Regular: ×2.00 &nbsp;|&nbsp; Night Diff: ×2.10</p>
                            <p class="text-gray-500">OT: ×2.60 &nbsp;|&nbsp; OT + ND: ×2.86</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            
            <div class="s-card">
                <div class="s-head">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Remittance Readiness</p>
                    <h3 class="text-base font-bold text-gray-800 mt-0.5">Government Contribution Status</h3>
                </div>
                <div class="s-body">
                    <?php
                        $contribs = [
                            ['label' => 'SSS',       'count' => $contributions['sss'],       'icon' => '🏛️',  'color' => '#015581'],
                            ['label' => 'PhilHealth', 'count' => $contributions['philhealth'], 'icon' => '🏥',  'color' => '#027c8b'],
                            ['label' => 'Pag-IBIG',  'count' => $contributions['pagibig'],   'icon' => '🏠',  'color' => '#f0b626'],
                            ['label' => 'TIN',        'count' => $contributions['tin'],       'icon' => '📋',  'color' => '#6b7280'],
                        ];
                        $total = max($contributions['total'], 1);
                    ?>

                    <p class="text-xs text-gray-400 mb-4"><?php echo e($contributions['total']); ?> employee(s) in employment records</p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $contribs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php $pct = round(($c['count'] / $total) * 100); ?>
                    <div class="contrib-row">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg leading-none"><?php echo e($c['icon']); ?></span>
                            <div>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($c['label']); ?></p>
                                <p class="text-[10px] text-gray-400"><?php echo e($c['count']); ?> / <?php echo e($contributions['total']); ?> on file</p>
                            </div>
                        </div>
                        <div class="text-right min-w-[80px]">
                            <p class="text-sm font-black" style="color:<?php echo e($c['color']); ?>;"><?php echo e($pct); ?>%</p>
                            <div class="gauge-track mt-1 w-20">
                                <div class="gauge-fill" style="width:<?php echo e($pct); ?>%;background:<?php echo e($c['color']); ?>;"></div>
                            </div>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contributions['total'] === 0): ?>
                    <p class="text-sm text-gray-400 italic text-center py-4">No employment details on record yet.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="s-card">
                <div class="s-head flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400"><?php echo e(now()->format('F Y')); ?></p>
                        <h3 class="text-base font-bold text-gray-800 mt-0.5">OT Burn Rate</h3>
                    </div>
                    <?php
                        $statusStyle = match($otBurnRate['status']) {
                            'critical' => 'background:#fee2e2;color:#991b1b;',
                            'warning'  => 'background:#fef9c3;color:#854d0e;',
                            default    => 'background:#dcfce7;color:#166534;',
                        };
                        $statusLabel = match($otBurnRate['status']) {
                            'critical' => '⚠ Over Limit',
                            'warning'  => '⚠ Near Limit',
                            default    => '✓ On Track',
                        };
                        $fillColor = match($otBurnRate['status']) {
                            'critical' => '#dc2626',
                            'warning'  => '#f0b626',
                            default    => '#027c8b',
                        };
                    ?>
                    <span class="badge" style="<?php echo e($statusStyle); ?>"><?php echo e($statusLabel); ?></span>
                </div>
                <div class="s-body">
                    
                    <div class="text-center mb-6">
                        <p class="text-5xl font-black text-gray-900"><?php echo e($otBurnRate['used']); ?></p>
                        <p class="text-sm text-gray-400 mt-1">of <strong><?php echo e($otBurnRate['budget']); ?></strong> budgeted hours used</p>
                    </div>

                    <div class="gauge-track mb-2" style="height:14px;">
                        <div class="gauge-fill" style="width:<?php echo e($otBurnRate['pct']); ?>%;background:<?php echo e($fillColor); ?>;height:14px;"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mb-6">
                        <span>0h</span>
                        <span class="font-bold" style="color:<?php echo e($fillColor); ?>;"><?php echo e($otBurnRate['pct']); ?>% burned</span>
                        <span><?php echo e($otBurnRate['budget']); ?>h</span>
                    </div>

                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Monthly OT Budget (hours)</label>
                        <input type="number" wire:model.live="otBudgetHours" min="1" step="1"
                               class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-[#027c8b] focus:ring-2 focus:ring-[#027c8b]/20"/>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topNDWorkers->count()): ?>
                    <div class="mt-5 border-t border-gray-50 pt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Top Night Diff This Month</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $topNDWorkers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="flex justify-between items-center py-1.5 text-sm">
                            <span class="text-gray-700 font-medium truncate max-w-[130px]"><?php echo e($w->name); ?></span>
                            <div class="flex gap-3 text-xs font-bold shrink-0">
                                <span class="brand-teal">ND: <?php echo e(number_format($w->total_nd, 1)); ?>h</span>
                                <span class="brand-accent">OT: <?php echo e(number_format($w->total_ot, 1)); ?>h</span>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="s-card">
                <div class="s-head">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Year-End Liability</p>
                    <h3 class="text-base font-bold text-gray-800 mt-0.5">13th Month Pay Accrual</h3>
                </div>
                <div class="s-body">
                    
                    <div class="flex flex-col items-center mb-6">
                        <?php $pct13 = $thirteenthMonth['pct']; ?>
                        <div class="relative w-36 h-36">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#015581" stroke-width="3"
                                        stroke-dasharray="<?php echo e($pct13); ?>, 100"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <p class="text-2xl font-black brand-primary"><?php echo e($pct13); ?>%</p>
                                <p class="text-[10px] text-gray-400 font-bold">accrued</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Month <strong><?php echo e($thirteenthMonth['months_elapsed']); ?></strong> of 12
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-3 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Accrued To Date</p>
                                <p class="text-2xl font-black text-gray-900">₱<?php echo e(number_format($thirteenthMonth['accrued'], 2)); ?></p>
                            </div>
                            <div class="kpi-icon brand-bg-p-lt">
                                <svg class="w-5 h-5 brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex justify-between items-center py-3 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Full Year Liability</p>
                                <p class="text-xl font-black text-gray-500">₱<?php echo e(number_format($thirteenthMonth['full_liability'], 2)); ?></p>
                            </div>
                            <div class="kpi-icon brand-bg-a-lt">
                                <svg class="w-5 h-5 brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thirteenthMonth['full_liability'] === 0.0): ?>
                        <p class="text-xs text-gray-400 italic pt-2 text-center">No payroll rates on record. Set monthly rates in employee payroll profiles.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div class="mt-4 border-t border-gray-50 pt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Year Progress</p>
                        <div class="flex gap-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($m = 1; $m <= 12; $m++): ?>
                            <div class="flex-1 rounded-sm h-5 flex items-center justify-center text-[9px] font-black
                                <?php echo e($m <= $thirteenthMonth['months_elapsed'] ? 'brand-bg-p text-white' : 'bg-gray-100 text-gray-300'); ?>"
                                title="<?php echo e(\Carbon\Carbon::create(null, $m)->format('M')); ?>">
                                <?php echo e(\Carbon\Carbon::create(null, $m)->format('M')[0]); ?>

                            </div>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/HR/payroll-compliance.blade.php ENDPATH**/ ?>