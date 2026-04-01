<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DHead extends Component
{
    // ─── Form Properties for Leave Entry ───
    public $form = [
        'leave_type' => '',
        'start_date' => '',
        'end_date' => '',
        'day_part' => 'Full',
        'total_days' => 0,
        'reason' => '',
        'reliever' => '',
    ];
    public $attachment = null;
    public $availableCredits = 18.5; // This would come from a credits table in real app

    // ─── Search & Modal State ───
    public $search = '';
    public $mySearch = '';
    public $showReviewModal = false;
    public $selectedLeave = null;
    public $remarks = '';

    // ─── Summary Card Properties (computed in render) ───
    public $pendingCount = 0;
    public $approvedThisMonth = 0;
    public $onLeaveToday = 0;

    // ─── Validation Rules ───
    protected $rules = [
        'form.leave_type' => 'required|string',
        'form.start_date' => 'required|date',
        'form.end_date' => 'required|date|after_or_equal:form.start_date',
        'form.reason' => 'required|string|min:5',
        'form.reliever' => 'nullable|string|max:255',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];

    // ─── Real-time validation for date changes ───
    public function updatedFormStartDate()
    {
        $this->calculateTotalDays();
    }

    public function updatedFormEndDate()
    {
        $this->calculateTotalDays();
    }

    public function updatedFormDayPart()
    {
        $this->calculateTotalDays();
    }

    protected function calculateTotalDays()
    {
        if ($this->form['start_date'] && $this->form['end_date']) {
            $start = Carbon::parse($this->form['start_date']);
            $end = Carbon::parse($this->form['end_date']);
            
            if ($start <= $end) {
                $days = $start->diffInDays($end) + 1;
                $multiplier = $this->form['day_part'] === 'Full' ? 1 : 0.5;
                $this->form['total_days'] = $days * $multiplier;
            } else {
                $this->form['total_days'] = 0;
            }
        } else {
            $this->form['total_days'] = 0;
        }
    }

    // ─── Submit Leave Application ───
    public function submitLeave()
    {
        $this->validate();

        try {
            $attachmentPath = null;
            if ($this->attachment) {
                $attachmentPath = $this->attachment->store('leave-attachments', 'public');
            }

            Leave::create([
                'user_id' => Auth::id(),
                'leave_type' => $this->form['leave_type'],
                'start_date' => $this->form['start_date'],
                'end_date' => $this->form['end_date'],
                'total_days' => $this->form['total_days'],
                'day_part' => $this->form['day_part'],
                'reason' => $this->form['reason'],
                'reliever' => $this->form['reliever'],
                'attachment' => $attachmentPath,
                'dept_head_status' => 'pending',
                'hr_status' => 'pending',
                'date_requested' => now(),
            ]);

            // Reset form
            $this->form = [
                'leave_type' => '',
                'start_date' => '',
                'end_date' => '',
                'day_part' => 'Full',
                'total_days' => 0,
                'reason' => '',
                'reliever' => '',
            ];
            $this->attachment = null;
            
            session()->flash('message', 'Leave application submitted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong. Please try again.');
        }
    }

    // ─── Open Review Modal ───
    public function openReviewModal($id)
    {
        $this->selectedLeave = Leave::with('user')->findOrFail($id);
        $this->remarks = $this->selectedLeave->dept_head_remarks ?? '';
        $this->showReviewModal = true;
    }

    // ─── Process Approval/Rejection ───
    public function process($status)
    {
        if (!$this->selectedLeave) return;

        $this->selectedLeave->update([
            'dept_head_status' => $status,
            'dept_head_remarks' => $this->remarks,
            'date_approved_dept' => now(),
        ]);

        $this->reset(['showReviewModal', 'selectedLeave', 'remarks']);
        session()->flash('message', 'Application has been ' . $status . ' successfully.');
    }

    // ─── Close Modal ───
    public function closeModal()
    {
        $this->reset(['showReviewModal', 'selectedLeave', 'remarks']);
    }

    // ─── Real-time listeners for search (Livewire handles automatically with .live) ───

    public function render()
    {
        // ─── INCOMING LEAVES (Department Head's team) ───
        // Note: In a real app, you'd filter by department/team.
        // For demo, we show all leaves with pending/approved/rejected status.
        $leavesQuery = Leave::with('user')
            ->where(function ($query) {
                $query->where('dept_head_status', 'pending')
                      ->orWhere('dept_head_status', 'approved')
                      ->orWhere('dept_head_status', 'rejected');
            });
        
        // Apply search filter for incoming requests
        if (!empty($this->search)) {
            $leavesQuery->where(function ($q) {
                $q->whereHas('user', function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('leave_type', 'like', '%' . $this->search . '%');
            });
        }
        
        $leaves = $leavesQuery->latest()->get();
        
        // ─── MY LEAVE REQUESTS (Submitted by the logged-in department head) ───
        $myLeavesQuery = Leave::where('user_id', Auth::id());
        
        if (!empty($this->mySearch)) {
            $myLeavesQuery->where(function ($q) {
                $q->where('leave_type', 'like', '%' . $this->mySearch . '%')
                  ->orWhere('reason', 'like', '%' . $this->mySearch . '%');
            });
        }
        
        $myLeaves = $myLeavesQuery->latest()->get();
        
        // ─── SUMMARY CARDS DATA ───
        $this->pendingCount = Leave::where('dept_head_status', 'pending')->count();
        
        $this->approvedThisMonth = Leave::where('dept_head_status', 'approved')
            ->whereMonth('date_approved_dept', Carbon::now()->month)
            ->whereYear('date_approved_dept', Carbon::now()->year)
            ->count();
        
        $today = Carbon::now()->toDateString();
        $this->onLeaveToday = Leave::where('dept_head_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
        
        return view('pages.users.dhead-leave', [
            'leaves' => $leaves,
            'myLeaves' => $myLeaves,
            'pendingCount' => $this->pendingCount,
            'approvedThisMonth' => $this->approvedThisMonth,
            'onLeaveToday' => $this->onLeaveToday,
            'availableCredits' => $this->availableCredits,
            'form' => $this->form,
        ])->layout('layouts.app');
    }
}