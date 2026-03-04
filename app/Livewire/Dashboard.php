<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        // Stats for the top cards
        $stats = [
            'total_items' => Medicine::count(),
            'active_stock' => Medicine::where('status', 'active')->count(),
            'low_stock' => Medicine::where('reorder_level', '>', 0)
                                    ->where('status', 'active')->count(),
        ];

        // Data for Category Distribution (Bar Chart)
       

        // Data for Stock Status (Doughnut Chart)
        $statusData = [
            Medicine::where('status', 'active')->count(),
            Medicine::where('status', 'inactive')->count(),
        ];

        return view('pages.Medmission.dashboard', [
            'stats' => $stats,
            'statusData' => $statusData,
            'lowStockItems' => Medicine::where('reorder_level', '>', 0)->take(5)->get()
        ])->layout('layouts.app');
    }
}