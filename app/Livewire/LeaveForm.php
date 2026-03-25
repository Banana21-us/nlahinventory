<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LeaveForm extends Component
{
    public $user;
    public $name;
    public $employee_number;
    public $email;
    public $department;
    public $leavetype;
    public $startdate;
    public $enddate;
    public $totaldays = 0;
    public $reason;
    
    protected $rules = [
        'leavetype' => 'required|string',
        'startdate' => 'required|date',
        'enddate' => 'required|date|after_or_equal:startdate',
        'reason' => 'required|string|min:5',
    ];
    
    protected $messages = [
        'leavetype.required' => 'Please select a leave type.',
        'startdate.required' => 'Please select a start date.',
        'enddate.required' => 'Please select an end date.',
        'enddate.after_or_equal' => 'End date must be after or equal to start date.',
        'reason.required' => 'Please provide a reason for your leave.',
        'reason.min' => 'Reason must be at least 5 characters.',
    ];
    
    public function mount()
    {
        // Get the authenticated user
        $this->user = Auth::user();
        
        // Check if user is authenticated
        if (!$this->user) {
            Log::error('No authenticated user found in LeaveForm mount');
            session()->flash('error', 'Please log in to access the leave form.');
            return;
        }
        
        // Auto-fill user information from the authenticated user
        $this->name = $this->user->username ?? 'N/A';
        $this->employee_number = $this->user->employee_number ?? 'N/A';
        $this->email = $this->user->email ?? 'N/A';
        $this->department = $this->user->department ?? 'N/A';
        
        // Log the values for debugging
        Log::info('LeaveForm mounted for user: ' . $this->user->id);
        Log::info('User data - Name: ' . $this->name . 
                  ', Employee: ' . $this->employee_number . 
                  ', Email: ' . $this->email . 
                  ', Department: ' . $this->department);
    }
    
    public function updatedStartdate()
    {
        $this->calculateTotalDays();
    }
    
    public function updatedEnddate()
    {
        $this->calculateTotalDays();
    }
    
    public function calculateTotalDays()
    {
        if ($this->startdate && $this->enddate) {
            $start = Carbon::parse($this->startdate);
            $end = Carbon::parse($this->enddate);
            $this->totaldays = $start->diffInDays($end) + 1;
        } else {
            $this->totaldays = 0;
        }
    }
    
    public function submit()
    {
        // Verify user is still authenticated
        $user = Auth::user();
        if (!$user) {
            session()->flash('error', 'Your session has expired. Please log in again.');
            return redirect()->route('login');
        }
        
        $this->validate();
        
        try {
            // Create leave request using your Leave model
            Leave::create([
                'user_id' => $user->id,
                'leavetype' => $this->leavetype,
                'department' => $this->department,
                'startdate' => $this->startdate,
                'enddate' => $this->enddate,
                'totaldays' => $this->totaldays,
                'reason' => $this->reason,
                'status' => 'pending',
                'approved_by' => null,
                'remarks' => null,
            ]);
            
            // Reset form fields (keep user info)
            $this->reset(['leavetype', 'startdate', 'enddate', 'totaldays', 'reason']);
            
            // Show success message
            session()->flash('message', 'Leave request submitted successfully!');
            
            // Dispatch event for scroll to top
            $this->dispatch('leave-submitted');
            
        } catch (\Exception $e) {
            Log::error('Error submitting leave request: ' . $e->getMessage());
            session()->flash('error', 'Failed to submit leave request. Please try again. Error: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('pages.users.leaveform');
    }
}