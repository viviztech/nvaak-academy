<?php

namespace App\Livewire\Admin\Exams;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\ExamSeries;

class Index extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $statusFilter  = '';
    public string $typeFilter    = '';
    public string $batchFilter   = '';
    public string $courseFilter  = '';
    public int    $perPage       = 15;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingBatchFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCourseFilter(): void
    {
        $this->resetPage();
    }

    public function publishExam(int $examId): void
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'published']);
        session()->flash('success', "Exam '{$exam->name}' published.");
    }

    public function unpublishExam(int $examId): void
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'draft']);
        session()->flash('success', "Exam '{$exam->name}' moved back to draft.");
    }

    public function goLive(int $examId): void
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'live']);
        session()->flash('success', "Exam '{$exam->name}' is now LIVE.");
    }

    public function markCompleted(int $examId): void
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['status' => 'completed']);
        session()->flash('success', "Exam '{$exam->name}' marked completed.");
    }

    public function duplicateExam(int $examId): void
    {
        $original = Exam::with(['sections', 'questions'])->findOrFail($examId);

        $copy = $original->replicate();
        $copy->name   = $original->name . ' (Copy)';
        $copy->code   = $original->code . '_COPY_' . now()->timestamp;
        $copy->status = 'draft';
        $copy->save();

        foreach ($original->sections as $section) {
            $newSection = $section->replicate();
            $newSection->exam_id = $copy->id;
            $newSection->save();
        }

        foreach ($original->questions as $eq) {
            $newEq = $eq->replicate();
            $newEq->exam_id = $copy->id;
            $newEq->save();
        }

        session()->flash('success', 'Exam duplicated successfully.');
        $this->resetPage();
    }

    public function deleteExam(int $examId): void
    {
        Exam::findOrFail($examId)->delete();
        session()->flash('success', 'Exam deleted.');
        $this->resetPage();
    }

    public function render()
    {
        $exams = Exam::query()
            ->with(['batch', 'subject', 'examSeries'])
            ->withCount('questions')
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%")
            )
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->typeFilter, fn ($q) => $q->where('exam_type', $this->typeFilter))
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->when($this->courseFilter, fn ($q) => $q->where('course_type', $this->courseFilter))
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $batches     = Batch::active()->orderBy('name')->get();
        $examSeries  = ExamSeries::active()->orderBy('name')->get();

        $stats = [
            'total'     => Exam::count(),
            'draft'     => Exam::where('status', 'draft')->count(),
            'published' => Exam::where('status', 'published')->count(),
            'live'      => Exam::where('status', 'live')->count(),
            'completed' => Exam::where('status', 'completed')->count(),
        ];

        return view('livewire.admin.exams.index', [
            'exams'      => $exams,
            'batches'    => $batches,
            'examSeries' => $examSeries,
            'stats'      => $stats,
        ])->layout('layouts.admin', ['title' => 'Exams']);
    }
}
