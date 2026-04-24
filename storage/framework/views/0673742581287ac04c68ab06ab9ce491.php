<div class="max-w-7xl mx-auto py-8 px-4 nlah-page-text-primary">
<style>
    .brand-bg-primary        { background-color: #015581; }
    .brand-bg-primary-light  { background-color: #e6f0f7; }
    .brand-text-primary      { color: #015581; }
    .brand-bg-accent         { background-color: #f0b626; }
    .brand-bg-accent-light   { background-color: #fef8e7; }
    .brand-text-accent       { color: #f0b626; }
    .brand-bg-teal           { background-color: #027c8b; }
    .brand-bg-teal-light     { background-color: #e6f4f5; }
    .brand-text-teal         { color: #027c8b; }
    .brand-btn-primary { background-color:#015581;color:#fff;transition:background-color .15s ease; }
    .brand-btn-primary:hover { background-color:#01406a; }
    .brand-btn-teal { background-color:#027c8b;color:#fff;transition:background-color .15s ease; }
    .brand-btn-teal:hover { background-color:#016070; }
    .brand-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(1,85,129,.2);border-color:#015581; }
    .search-focus:focus { outline:none;box-shadow:0 0 0 3px rgba(2,124,139,.2);border-color:#027c8b; }
    .brand-row-hover:hover { background-color:#f0f7fc; }
    .brand-edit-btn { background-color:#e6f0f7;color:#015581; }
    .brand-edit-btn:hover { background-color:#cde0ef; }
    @keyframes shrink { from { width:100% } to { width:0% } }
</style>

    
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg brand-bg-primary-light">
                <svg class="w-6 h-6 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-gray-400">HR</p>
                <h1 class="text-xl font-bold text-gray-800 leading-tight">Attendance Management</h1>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <?php
            $cards = [
                ['label'=>'Total Records',  'value'=>$stats['total'],    'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg'=>'brand-bg-primary-light', 'text'=>'brand-text-primary'],
                ['label'=>'On Time',        'value'=>$stats['on_time'],  'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg'=>'bg-green-50', 'text'=>'text-green-700'],
                ['label'=>'Late',           'value'=>$stats['late'],     'icon'=>'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'bg'=>'bg-red-50', 'text'=>'text-red-700'],
                ['label'=>'Half Day',       'value'=>$stats['half_day'], 'icon'=>'M12 3v1m0 16v1m9-9h-1M4 12H3m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z', 'bg'=>'bg-yellow-50', 'text'=>'text-yellow-700'],
                ['label'=>'Absent',         'value'=>$stats['absent'],   'icon'=>'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'bg'=>'bg-gray-100', 'text'=>'text-gray-600'],
                ['label'=>'Overtime',       'value'=>$stats['overtime'], 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z', 'bg'=>'brand-bg-teal-light', 'text'=>'brand-text-teal'],
            ];
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 flex items-center gap-3">
                <div class="p-2 rounded-lg <?php echo e($card['bg']); ?> shrink-0">
                    <svg class="w-5 h-5 <?php echo e($card['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($card['icon']); ?>"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold"><?php echo e($card['label']); ?></p>
                    <p class="text-2xl font-bold text-gray-800 leading-none"><?php echo e($card['value']); ?></p>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    
    <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden mb-6"
         x-data="{ open: <?php if ((object) ('showUpload') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUpload'->value()); ?>')<?php echo e('showUpload'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showUpload'); ?>')<?php endif; ?> }">

        <button @click="open = !open"
            class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors focus:outline-none">
            <div class="flex items-center">
                <div class="p-2 rounded-lg mr-4 brand-bg-primary-light">
                    <svg class="w-5 h-5 brand-text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" x-show="!open"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" x-show="open" style="display:none"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Import MDB File</h2>
                    <p class="text-xs text-gray-400">ZKTeco att2000.mdb biometric export</p>
                </div>
            </div>
            <span class="text-sm font-medium brand-text-primary" x-text="open ? 'Minimize' : 'Upload MDB'"></span>
        </button>

        <div x-show="open" x-collapse class="border-t border-gray-100 p-6 bg-gray-50/30">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadResult): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadResult['type'] === 'success'): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-bold text-green-800">Import successful</p>
                            <p class="text-green-700 mt-1">
                                <?php echo e($uploadResult['imported']); ?> records imported &middot;
                                <?php echo e($uploadResult['skipped']); ?> skipped (duplicates) &middot;
                                <?php echo e($uploadResult['dates']); ?> date(s) processed
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($uploadResult['unmatched'] ?? 0) > 0): ?>
                                <p class="text-yellow-700 mt-1 font-semibold">
                                    ⚠ <?php echo e($uploadResult['unmatched']); ?> punch records could not be matched to any employee.
                                    Make sure each employee has a <strong>Biometric ID</strong> set in their Employee profile that matches the ZKTeco device ID.
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-bold text-red-800">Import failed</p>
                            <p class="text-red-700 mt-1"><?php echo e($uploadResult['message']); ?></p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form wire:submit.prevent="uploadAndProcess" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1">
                        MDB File (att2000.mdb)
                    </label>
                    <input type="file" wire:model="mdbFile" accept=".mdb"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0 file:text-sm file:font-bold
                               file:brand-btn-primary file:cursor-pointer
                               bg-white border border-gray-300 rounded-md p-1"/>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mdbFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 block"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <button type="submit"
                    class="brand-btn-primary text-sm font-bold py-2 px-8 rounded shadow-md active:scale-95 flex items-center gap-2">
                    <span wire:loading.remove wire:target="uploadAndProcess">
                        <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import & Process
                    </span>
                    <span wire:loading wire:target="uploadAndProcess" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Processing…
                    </span>
                </button>
            </form>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentUploads->isNotEmpty()): ?>
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Recent Uploads</p>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-1 pr-4">File</th>
                                <th class="py-1 pr-4">Imported</th>
                                <th class="py-1 pr-4">Skipped</th>
                                <th class="py-1 pr-4">Dates</th>
                                <th class="py-1 pr-4">Status</th>
                                <th class="py-1">Uploaded</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentUploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ul): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="py-1.5 pr-4 font-mono text-xs text-gray-600"><?php echo e($ul->filename); ?></td>
                                    <td class="py-1.5 pr-4 text-gray-700"><?php echo e($ul->records_imported); ?></td>
                                    <td class="py-1.5 pr-4 text-gray-500"><?php echo e($ul->records_skipped); ?></td>
                                    <td class="py-1.5 pr-4 text-gray-500"><?php echo e(count($ul->dates_processed ?? [])); ?></td>
                                    <td class="py-1.5 pr-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ul->status === 'success'): ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Success</span>
                                        <?php elseif($ul->status === 'partial'): ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Partial</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Failed</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="py-1.5 text-gray-400 text-xs"><?php echo e($ul->created_at->format('M d, Y h:i A')); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">

        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-3 justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg brand-bg-teal-light">
                    <svg class="w-4 h-4 brand-text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Daily Attendance</h3>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                    <?php echo e($records->count()); ?> <?php echo e(Str::plural('record', $records->count())); ?>

                </span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                
                <input type="date" wire:model.live="selectedDate"
                    class="search-focus text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white"/>

                
                <select wire:model.live="statusFilter"
                    class="search-focus text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white">
                    <option value="">All Statuses</option>
                    <option value="on_time">On Time</option>
                    <option value="late_am">Late AM</option>
                    <option value="late_pm">Late PM</option>
                    <option value="late_both">Late AM &amp; PM</option>
                    <option value="late">Late (Nurse)</option>
                    <option value="half_day_am">Half Day AM</option>
                    <option value="half_day_pm">Half Day PM</option>
                    <option value="overtime">Overtime</option>
                    <option value="absent">Absent</option>
                </select>

                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search employee…"
                        class="search-focus pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-lg w-48"/>
                </div>

                
                <button
                    @click="if(confirm('Re-process attendance for <?php echo e($selectedDate); ?>?\nThis will overwrite existing records for that day.')) $wire.processSelectedDate()"
                    class="brand-edit-btn text-sm font-semibold px-3 py-2 rounded-lg flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" wire:loading.class="animate-spin" wire:target="processSelectedDate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span wire:loading.remove wire:target="processSelectedDate">Re-process</span>
                    <span wire:loading wire:target="processSelectedDate">Processing…</span>
                </button>
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dept / Shift</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">AM In</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">AM Out</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">PM In</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">PM Out</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Hrs</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">OT Hrs</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Late Min</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $emp    = $rec->user?->employee;
                            $detail = $rec->user?->employmentDetail;
                            $isNurse = $rec->shift_type === 'nurse';

                            $statusConfig = [
                                'on_time'     => ['bg'=>'#dcfce7','color'=>'#166534','border'=>'#86efac','label'=>'On Time'],
                                'late_am'     => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Late AM'],
                                'late_pm'     => ['bg'=>'#fef3c7','color'=>'#92400e','border'=>'#fcd34d','label'=>'Late PM'],
                                'late_both'   => ['bg'=>'#fee2e2','color'=>'#991b1b','border'=>'#fca5a5','label'=>'Late AM & PM'],
                                'late'        => ['bg'=>'#fef9c3','color'=>'#854d0e','border'=>'#fde047','label'=>'Late'],
                                'half_day_am' => ['bg'=>'#ede9fe','color'=>'#6b21a8','border'=>'#c4b5fd','label'=>'Half Day AM'],
                                'half_day_pm' => ['bg'=>'#ede9fe','color'=>'#6b21a8','border'=>'#c4b5fd','label'=>'Half Day PM'],
                                'overtime'    => ['bg'=>'#e6f4f5','color'=>'#027c8b','border'=>'#a5d8dd','label'=>'Overtime'],
                                'absent'      => ['bg'=>'#f3f4f6','color'=>'#6b7280','border'=>'#d1d5db','label'=>'Absent'],
                            ];
                            $sc = $statusConfig[$rec->status] ?? $statusConfig['absent'];
                        ?>
                        <tr class="brand-row-hover transition-colors">

                            
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 brand-bg-primary">
                                        <?php echo e(strtoupper(substr($rec->user?->name ?? '?', 0, 1))); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900"><?php echo e($rec->user?->name ?? 'Unknown'); ?></p>
                                        <p class="text-xs text-gray-400 font-mono"><?php echo e($rec->user?->employee_number); ?></p>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-700"><?php echo e($detail?->department ?? '—'); ?></p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-xs font-semibold rounded-full
                                    <?php echo e($isNurse ? 'bg-purple-100 text-purple-700' : 'bg-blue-50 text-blue-700'); ?>">
                                    <?php echo e($isNurse ? 'Nurse/Duty' : 'Office'); ?>

                                </span>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isNurse): ?>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($rec->clock_in ? $rec->clock_in->format('h:i A') : '—'); ?>

                                    </span>
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->am_in): ?>
                                        <span class="<?php echo e(\Carbon\Carbon::parse($rec->am_in)->format('H:i') > '08:15' ? 'text-red-600 font-bold' : 'text-gray-700'); ?>">
                                            <?php echo e(\Carbon\Carbon::parse($rec->am_in)->format('h:i A')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-300">—</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isNurse): ?>
                                    <span class="text-gray-300">—</span>
                                <?php else: ?>
                                    <?php echo e($rec->am_out ? \Carbon\Carbon::parse($rec->am_out)->format('h:i A') : '—'); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isNurse): ?>
                                    <span class="text-gray-300">—</span>
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->pm_in): ?>
                                        <span class="<?php echo e(\Carbon\Carbon::parse($rec->pm_in)->format('H:i') > '13:15' ? 'text-red-600 font-bold' : 'text-gray-700'); ?>">
                                            <?php echo e(\Carbon\Carbon::parse($rec->pm_in)->format('h:i A')); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-300">—</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isNurse): ?>
                                    <?php echo e($rec->clock_out ? $rec->clock_out->format('h:i A') : '—'); ?>

                                <?php else: ?>
                                    <?php echo e($rec->pm_out ? \Carbon\Carbon::parse($rec->pm_out)->format('h:i A') : '—'); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">
                                <?php echo e($rec->total_hours > 0 ? number_format($rec->total_hours, 1).'h' : '—'); ?>

                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->overtime_hours > 0): ?>
                                    <span class="font-bold brand-text-teal">+<?php echo e(number_format($rec->overtime_hours, 1)); ?>h</span>
                                <?php else: ?>
                                    <span class="text-gray-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center text-sm">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->late_minutes > 0): ?>
                                    <span class="font-bold text-red-600"><?php echo e($rec->late_minutes); ?>m</span>
                                <?php else: ?>
                                    <span class="text-gray-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      style="background-color:<?php echo e($sc['bg']); ?>;color:<?php echo e($sc['color']); ?>;border:1px solid <?php echo e($sc['border']); ?>">
                                    <?php echo e($sc['label']); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rec->email_sent): ?>
                                    <svg title="Alert email sent" class="inline w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="10" class="px-6 py-14 text-center">
                                <div class="flex flex-col items-center text-gray-400">
                                    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium">
                                        <?php echo e($search || $statusFilter ? 'No records match your filter.' : 'No attendance data for this date.'); ?>

                                    </p>
                                    <p class="text-xs mt-1">
                                        <?php echo e($search || $statusFilter ? 'Try adjusting the filter.' : 'Upload an MDB file above or click Re-process to generate records.'); ?>

                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($toastMessage): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-black/5">
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full brand-bg-teal-light">
                    <svg class="w-5 h-5 brand-text-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Done</p>
                    <p class="mt-0.5 text-sm text-gray-500"><?php echo e($toastMessage); ?></p>
                </div>
            </div>
            <div class="h-1" style="background-color:#f0b626;animation:shrink 4s linear forwards;"></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($toastError): ?>
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             class="fixed top-5 right-5 z-[60] w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-lg ring-1 ring-red-200">
            <div class="p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-full bg-red-50">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-gray-900">Error</p>
                    <p class="mt-0.5 text-sm text-gray-500"><?php echo e($toastError); ?></p>
                </div>
            </div>
            <div class="h-1 bg-red-400" style="animation:shrink 6s linear forwards;"></div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH C:\Users\JOHNPAUL\OneDrive\Documents\GitHub\nlahinventory\resources\views/pages/HR/attendance-management.blade.php ENDPATH**/ ?>