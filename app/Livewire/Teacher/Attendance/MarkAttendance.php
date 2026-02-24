<?php

namespace App\Livewire\Teacher\Attendance;

use Livewire\Component;
use App\Models\Batch;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MarkAttendance extends Component
{
    public $selectedBatch = '';
    public $date;
    public $attendanceData = [];
    public $batches = [];
    public $students = [];
    public $saved = false;

    public function mount()
    {
        $this->date = Carbon::today()->toDateString();
        $this->loadBatches();
    }

    public function loadBatches()
    {
        $user   = Auth::user();
        $faculty = $user->faculty ?? null;

        if ($faculty) {
            $this->batches = Batch::where('faculty_id', $faculty->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->toArray();
        } else {
            $this->batches = [];
        }
    }

    public function updatedSelectedBatch()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        if (!$this->selectedBatch) {
            $this->students       = [];
            $this->attendanceData = [];
            return;
        }

        $this->students = Student::whereHas('batches', function ($q) {
            $q->where('batches.id', $this->selectedBatch);
        })->where('is_active', true)->orderBy('name')->get()->toArray();

        // Pre-fill existing attendance records for the selected date
        $existing = Attendance::where('batch_id', $this->selectedBatch)
            ->whereDate('date', $this->date)
            ->pluck('status', 'student_id')
            ->toArray();

        $this->attendanceData = [];
        foreach ($this->students as $student) {
            $this->attendanceData[$student['id']] = $existing[$student['id']] ?? 'present';
        }
    }

    public function saveAttendance()
    {
        if (!$this->selectedBatch || empty($this->attendanceData)) {
            return;
        }

        foreach ($this->attendanceData as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'batch_id'   => $this->selectedBatch,
                    'date'       => $this->date,
                ],
                [
                    'status'      => $status,
                    'marked_by'   => Auth::id(),
                    'marked_at'   => now(),
                ]
            );
        }

        $this->saved = true;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Attendance saved successfully.']);
    }

    public function render()
    {
        return view('livewire.teacher.attendance.mark-attendance')
            ->layout('layouts.teacher', ['title' => 'Mark Attendance']);
    }
}
