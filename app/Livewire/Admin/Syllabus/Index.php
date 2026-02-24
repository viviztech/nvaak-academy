<?php

namespace App\Livewire\Admin\Syllabus;

use Livewire\Component;
use App\Models\Subject;
use App\Models\Batch;
use App\Models\SyllabusCoverage;

class Index extends Component
{
    public ?int $batchId = null;
    public ?int $subjectId = null;

    public function render()
    {
        $batches  = Batch::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $coverages = SyllabusCoverage::with(['chapter', 'subject', 'batch', 'faculty'])
            ->when($this->batchId, fn($q) => $q->where('batch_id', $this->batchId))
            ->when($this->subjectId, fn($q) => $q->where('subject_id', $this->subjectId))
            ->latest('taught_date')
            ->paginate(20);

        // Aggregate % per subject per batch
        $summary = [];
        if ($this->batchId) {
            foreach ($subjects as $subject) {
                $total   = SyllabusCoverage::where('batch_id', $this->batchId)
                    ->where('subject_id', $subject->id)->count();
                $covered = SyllabusCoverage::where('batch_id', $this->batchId)
                    ->where('subject_id', $subject->id)
                    ->where('is_completed', true)->count();
                if ($total > 0) {
                    $summary[] = [
                        'subject'  => $subject->name,
                        'total'    => $total,
                        'covered'  => $covered,
                        'percent'  => round(($covered / $total) * 100),
                    ];
                }
            }
        }

        return view('livewire.admin.syllabus.index', [
            'batches'   => $batches,
            'subjects'  => $subjects,
            'coverages' => $coverages,
            'summary'   => $summary,
        ])->layout('layouts.admin', ['title' => 'Syllabus Coverage']);
    }
}
