<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use App\Models\Batch;
use App\Models\Attendance;
use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MarkAttendance extends Component
{
    public string $selectedBatchId = '';
    public string $selectedDate    = '';
    public array  $attendanceData  = [];
    public bool   $isSaved         = false;
    public $batch                  = null;
    public array $students         = [];

    public function mount(): void
    {
        $this->selectedDate = today()->toDateString();
    }

    public function updatedSelectedBatchId(): void
    {
        $this->loadStudents();
    }

    public function updatedSelectedDate(): void
    {
        if ($this->selectedBatchId) {
            $this->loadStudents();
        }
    }

    public function loadStudents(): void
    {
        $this->batch = Batch::with('students.user')->find($this->selectedBatchId);

        if (! $this->batch) {
            return;
        }

        $this->students = $this->batch->students->map(fn ($s) => [
            'id'   => $s->id,
            'name' => $s->user->name,
            'code' => $s->student_code,
            'roll' => $s->pivot->roll_number ?? '-',
        ])->toArray();

        $existing = Attendance::where('batch_id', $this->selectedBatchId)
            ->where('date', $this->selectedDate)
            ->pluck('status', 'student_id')
            ->toArray();

        foreach ($this->students as $s) {
            $this->attendanceData[$s['id']] = $existing[$s['id']] ?? 'present';
        }

        $this->isSaved = ! empty($existing);
    }

    public function setAll(string $status): void
    {
        foreach ($this->students as $s) {
            $this->attendanceData[$s['id']] = $status;
        }
    }

    public function saveAttendance(): void
    {
        if (! $this->selectedBatchId || ! $this->selectedDate) {
            return;
        }

        $institute = Institute::first();

        foreach ($this->attendanceData as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'batch_id'   => $this->selectedBatchId,
                    'student_id' => $studentId,
                    'date'       => $this->selectedDate,
                ],
                [
                    'institute_id' => $institute->id,
                    'status'       => $status,
                    'marked_by'    => Auth::id(),
                ]
            );
        }

        $this->isSaved = true;
        session()->flash('success', 'Attendance saved for ' . Carbon::parse($this->selectedDate)->format('d M Y'));
    }

    public function render()
    {
        return view('livewire.admin.attendance.mark-attendance', [
            'batches' => Batch::where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Mark Attendance']);
    }
}
