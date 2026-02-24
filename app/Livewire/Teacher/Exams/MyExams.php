<?php

namespace App\Livewire\Teacher\Exams;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Exam;
use App\Models\Batch;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;

class MyExams extends Component
{
    use WithPagination;

    public $search    = '';
    public $statusFilter = '';
    public $batchFilter  = '';
    public $batches   = [];

    public function mount()
    {
        $user   = Auth::user();
        $faculty = $user->faculty ?? null;

        if ($faculty) {
            $this->batches = Batch::where('faculty_id', $faculty->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->toArray();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingBatchFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user   = Auth::user();
        $faculty = $user->faculty ?? null;

        $batchIds = collect($this->batches)->pluck('id');

        $exams = Exam::whereIn('batch_id', $batchIds)
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->batchFilter, function ($q) {
                $q->where('batch_id', $this->batchFilter);
            })
            ->with('batch')
            ->orderByDesc('start_time')
            ->paginate(15);

        // Enrich with attempt counts
        $exams->getCollection()->transform(function ($exam) {
            $exam->attempt_count    = ExamAttempt::where('exam_id', $exam->id)->count();
            $exam->completed_count  = ExamAttempt::where('exam_id', $exam->id)->where('status', 'completed')->count();
            $exam->avg_score        = ExamAttempt::where('exam_id', $exam->id)
                ->where('status', 'completed')
                ->avg('score');
            return $exam;
        });

        return view('livewire.teacher.exams.my-exams', compact('exams'))
            ->layout('layouts.teacher', ['title' => 'My Exams']);
    }
}
