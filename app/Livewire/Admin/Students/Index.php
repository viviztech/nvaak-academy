<?php

namespace App\Livewire\Admin\Students;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Batch;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $batchFilter = '';
    public string $statusFilter = '';
    public int $perPage = 15;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBatchFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function getStudentsProperty()
    {
        return Student::with(['user', 'batches'])
            ->when($this->search, function ($q) {
                $q->where(function ($sq) {
                    $sq->where('student_code', 'like', "%{$this->search}%")
                       ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$this->search}%")
                           ->orWhere('phone', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->batchFilter, fn($q) => $q->whereHas('batches', fn($bq) => $bq->where('batches.id', $this->batchFilter)))
            ->when($this->statusFilter !== '', function ($q) {
                if ($this->statusFilter === 'active') {
                    $q->whereHas('user', fn($uq) => $uq->where('is_active', true));
                } else {
                    $q->whereHas('user', fn($uq) => $uq->where('is_active', false));
                }
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function getBatchesProperty()
    {
        return Batch::where('is_active', true)->orderBy('name')->get();
    }

    public function toggleActive(int $studentId): void
    {
        $student = Student::with('user')->findOrFail($studentId);
        if ($student->user) {
            $student->user->update(['is_active' => !$student->user->is_active]);
            session()->flash('success', 'Student status updated successfully.');
        }
    }

    public function deleteStudent(int $studentId): void
    {
        $student = Student::findOrFail($studentId);
        $student->delete();
        session()->flash('success', 'Student removed successfully.');
        $this->resetPage();
    }

    public function render()
    {
        $totalStudents  = Student::count();
        $activeStudents = Student::whereHas('user', fn($q) => $q->where('is_active', true))->count();
        $inactiveStudents = Student::whereHas('user', fn($q) => $q->where('is_active', false))->count();

        return view('livewire.admin.students.index', [
            'students'         => $this->students,
            'allBatches'       => $this->batches,
            'totalStudents'    => $totalStudents,
            'activeStudents'   => $activeStudents,
            'inactiveStudents' => $inactiveStudents,
        ])->layout('layouts.admin', ['title' => 'Students']);
    }
}
