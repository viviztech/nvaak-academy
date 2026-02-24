<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\FeeInstallment;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user    = Auth::user();
        $student = $user->student;

        // Upcoming exams for the student's batch(es)
        $upcomingExams = collect();
        $recentResults = collect();
        $feesDue       = 0;
        $nextDueDate   = null;
        $attendancePct = 0;

        if ($student) {
            $batchIds = $student->batches()->pluck('batches.id');

            $upcomingExams = Exam::where('status', 'published')
                ->where('start_time', '>', now())
                ->whereIn('batch_id', $batchIds)
                ->orderBy('start_time')
                ->take(5)
                ->get();

            $recentResults = ExamAttempt::with('exam')
                ->where('student_id', $student->id)
                ->where('status', 'completed')
                ->latest('submitted_at')
                ->take(5)
                ->get();

            // Attendance percentage (current month)
            $totalClasses = Attendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->count();
            $presentCount = Attendance::where('student_id', $student->id)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'present')
                ->count();
            $attendancePct = $totalClasses > 0
                ? round(($presentCount / $totalClasses) * 100)
                : 0;

            // Pending fees
            $pendingInstallment = FeeInstallment::where('student_id', $student->id)
                ->whereIn('status', ['pending', 'overdue'])
                ->orderBy('due_date')
                ->first();
            if ($pendingInstallment) {
                $feesDue     = $pendingInstallment->amount;
                $nextDueDate = $pendingInstallment->due_date;
            }
        }

        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.student.dashboard', compact(
            'upcomingExams',
            'recentResults',
            'attendancePct',
            'feesDue',
            'nextDueDate',
            'announcements',
            'student'
        ))->layout('layouts.student', ['title' => 'My Dashboard']);
    }
}
