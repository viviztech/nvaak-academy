<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\Student;
use App\Models\Batch;
use App\Models\FeePayment;
use App\Models\Exam;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_enquiries'    => Enquiry::count(),
            'new_enquiries'      => Enquiry::where('status', 'new')->count(),
            'pending_admissions' => Admission::whereIn('status', ['submitted', 'under_review'])->count(),
            'total_students'     => Student::where('is_active', true)->count(),
            'total_batches'      => Batch::where('is_active', true)->count(),
            'fees_this_month'    => FeePayment::where('status', 'completed')
                                        ->whereMonth('payment_date', Carbon::now()->month)
                                        ->sum('amount_paid'),
            'upcoming_exams'     => Exam::where('status', 'published')
                                        ->where('start_time', '>', now())
                                        ->count(),
        ];

        $recentEnquiries = Enquiry::with('assignedTo')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentEnquiries'))
            ->layout('layouts.admin', ['title' => 'Admin Dashboard']);
    }
}
