<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Display error messages -->
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Display success messages -->
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif
            
            <!-- Display department warning -->
            @if(empty($department) || $department === 'Not Assigned')
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">
                        ⚠️ Warning: Your department is not assigned in the system. 
                        Please contact HR to update your profile before submitting a leave request.
                    </span>
                </div>
            @endif
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Leave Application Form</h1>
                    <p class="text-gray-600 mt-1">Please fill out the form below to request leave</p>
                </div>
                
                <form wire:submit.prevent="submit">
                    <!-- Employee Information (Auto-filled from login) -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5 mb-6 border border-blue-100">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Employee Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Employee Number
                                </label>
                                <input type="text" 
                                       value="{{ $employee_number }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                       readonly 
                                       disabled>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Full Name
                                </label>
                                <input type="text" 
                                       value="{{ $name }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                       readonly 
                                       disabled>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       value="{{ $email }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                       readonly 
                                       disabled>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Department
                                </label>
                                <input type="text" 
                                       value="{{ $department }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                       readonly 
                                       disabled>
                            </div>
                        </div>
                        
                        <!-- Display verification badge -->
                        <div class="mt-3 pt-3 border-t border-blue-200">
                            <div class="flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Verified employee information from your account
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leave Details -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Leave Details
                        </h2>
                        
                        <!-- Leave Type -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="leavetype">
                                Leave Type <span class="text-red-500">*</span>
                            </label>
                            <select id="leavetype" 
                                    wire:model="leavetype" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Leave Type</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Vacation Leave">Vacation Leave</option>
                                <option value="Pay Off">Pay Off</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Paternity Leave">Paternity Leave</option>
                                <option value="Leave Without Pay">Leave Without Pay</option>
                                <option value="Sympathetic Leave">Sympathetic Leave</option>
                                <option value="Special Leave">Special Leave</option>
                                <option value="Leave Without Pay">Birthday Leave</option>
                                <option value="Single Parent Leave">Single Parent Leave</option>
                            </select>
                            @error('leavetype') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="startdate">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="startdate" 
                                       wire:model="startdate" 
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('startdate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="enddate">
                                    End Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="enddate" 
                                       wire:model="enddate" 
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('enddate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <!-- Total Days (Auto-calculated) -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Total Days
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       wire:model="totaldays" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700"
                                       readonly 
                                       disabled>
                                @if($totaldays > 0)
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-sm text-gray-500">days</span>
                                    </div>
                                @endif
                            </div>
                            @if($totaldays > 0)
                                <p class="text-sm text-green-600 mt-1">
                                    ✅ Total leave days: <strong>{{ $totaldays }}</strong> day(s)
                                </p>
                            @endif
                        </div>
                        
                        <!-- Reason -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="reason">
                                Reason for Leave <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reason" 
                                      wire:model="reason" 
                                      rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Please provide details about your leave request..."></textarea>
                            @error('reason') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="window.location.href='{{ route('medmission.dashboard') }}'"
                                class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300">
                            Cancel
                        </button>
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                            <span wire:loading.remove>Submit</span>
                            <span wire:loading>Submitting...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', function () {
        Livewire.on('leave-submitted', () => {
            // Scroll to top to show success message
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
@endpush