<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Attendance;
use App\Services\AttendanceReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceCalendar extends Component
{
    public string $currentMonth = '';

    public function mount(): void
    {
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function getStudentProperty()
    {
        return Auth::user()->student ?? null;
    }

    public function getCalendarProperty(): array
    {
        if (! $this->student) {
            return [];
        }

        $service = app(AttendanceReportService::class);

        // Get first active batch for the student
        $batch = $this->student->batches()->where('is_active', true)->first();

        if (! $batch) {
            return [];
        }

        return $service->getStudentAttendanceCalendar(
            $this->student->id,
            $batch->id,
            $this->currentMonth
        );
    }

    public function getStatsProperty(): array
    {
        if (! $this->student) {
            return ['present' => 0, 'absent' => 0, 'late' => 0, 'on_leave' => 0];
        }

        $batch = $this->student->batches()->where('is_active', true)->first();

        if (! $batch) {
            return ['present' => 0, 'absent' => 0, 'late' => 0, 'on_leave' => 0];
        }

        $start = Carbon::parse($this->currentMonth . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $records = Attendance::where('student_id', $this->student->id)
            ->where('batch_id', $batch->id)
            ->whereBetween('date', [$start, $end])
            ->get();

        return [
            'present'  => $records->where('status', 'present')->count(),
            'absent'   => $records->where('status', 'absent')->count(),
            'late'     => $records->where('status', 'late')->count(),
            'on_leave' => $records->where('status', 'on_leave')->count(),
        ];
    }

    public function getOverallPercentageProperty(): float
    {
        if (! $this->student) {
            return 0.0;
        }

        $batch = $this->student->batches()->where('is_active', true)->first();

        if (! $batch) {
            return 0.0;
        }

        return Attendance::getAttendancePercentage($this->student->id, $batch->id);
    }

    public function prevMonth(): void
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')
            ->subMonth()
            ->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')
            ->addMonth()
            ->format('Y-m');
    }

    public function render()
    {
        return view('livewire.student.attendance-calendar', [
            'calendar'           => $this->calendar,
            'stats'              => $this->stats,
            'overallPercentage'  => $this->overallPercentage,
            'monthLabel'         => Carbon::parse($this->currentMonth . '-01')->format('F Y'),
        ])->layout('layouts.student', ['title' => 'My Attendance']);
    }
}
