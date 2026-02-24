<?php

namespace App\Livewire\Admin\IAS;

use App\Models\Batch;
use App\Models\IasAnswerSubmission;
use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class AnswerSubmissions extends Component
{
    use WithPagination;

    public string $statusFilter  = 'submitted';
    public string $batchFilter   = '';
    public string $subjectFilter = '';
    public int $perPage          = 15;

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function getSubmissionsProperty()
    {
        return IasAnswerSubmission::with(['student.user', 'subject', 'batch', 'evaluation'])
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->when($this->subjectFilter, fn ($q) => $q->where('subject_id', $this->subjectFilter))
            ->latest('submitted_at')
            ->paginate($this->perPage);
    }

    public function getBatchesProperty()
    {
        return Batch::active()->orderBy('name')->get();
    }

    public function getSubjectsProperty()
    {
        return Subject::active()->orderBy('name')->get();
    }

    public function getStatusCountsProperty(): array
    {
        return [
            'submitted'        => IasAnswerSubmission::where('status', 'submitted')->count(),
            'under_evaluation' => IasAnswerSubmission::where('status', 'under_evaluation')->count(),
            'evaluated'        => IasAnswerSubmission::where('status', 'evaluated')->count(),
            'returned'         => IasAnswerSubmission::where('status', 'returned')->count(),
        ];
    }

    public function markUnderEvaluation(int $id): void
    {
        IasAnswerSubmission::where('id', $id)->update(['status' => 'under_evaluation']);
        session()->flash('success', 'Submission marked as under evaluation.');
    }

    public function render()
    {
        return view('livewire.admin.ias.answer-submissions')
            ->layout('layouts.admin', ['title' => 'IAS Answer Submissions']);
    }
}
