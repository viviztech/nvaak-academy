<?php

namespace App\Livewire\Teacher\Syllabus;

use Livewire\Component;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\SyllabusChapter;
use App\Models\SyllabusCoverage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CoverageUpdate extends Component
{
    public $selectedBatch   = '';
    public $selectedSubject = '';
    public $batches         = [];
    public $subjects        = [];
    public $chapters        = [];
    public $coverageData    = [];
    public $coverageNotes   = [];
    public $saved           = false;

    public function mount()
    {
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
        $this->selectedSubject = '';
        $this->chapters        = [];
        $this->coverageData    = [];

        if (!$this->selectedBatch) {
            $this->subjects = [];
            return;
        }

        // Load subjects associated with this batch
        $this->subjects = Subject::whereHas('batches', function ($q) {
            $q->where('batches.id', $this->selectedBatch);
        })->orderBy('name')->get()->toArray();
    }

    public function updatedSelectedSubject()
    {
        $this->loadChapters();
    }

    public function loadChapters()
    {
        if (!$this->selectedBatch || !$this->selectedSubject) {
            $this->chapters     = [];
            $this->coverageData = [];
            return;
        }

        $this->chapters = SyllabusChapter::where('batch_id', $this->selectedBatch)
            ->where('subject_id', $this->selectedSubject)
            ->orderBy('order')
            ->get()
            ->toArray();

        $existing = SyllabusCoverage::where('batch_id', $this->selectedBatch)
            ->where('subject_id', $this->selectedSubject)
            ->pluck('notes', 'chapter_id')
            ->toArray();

        $existingCovered = SyllabusCoverage::where('batch_id', $this->selectedBatch)
            ->where('subject_id', $this->selectedSubject)
            ->where('is_covered', true)
            ->pluck('is_covered', 'chapter_id')
            ->toArray();

        $this->coverageData  = [];
        $this->coverageNotes = [];

        foreach ($this->chapters as $chapter) {
            $this->coverageData[$chapter['id']]  = $existingCovered[$chapter['id']] ?? false;
            $this->coverageNotes[$chapter['id']] = $existing[$chapter['id']] ?? '';
        }
    }

    public function saveCoverage()
    {
        if (!$this->selectedBatch || !$this->selectedSubject || empty($this->coverageData)) {
            return;
        }

        foreach ($this->coverageData as $chapterId => $isCovered) {
            SyllabusCoverage::updateOrCreate(
                [
                    'batch_id'   => $this->selectedBatch,
                    'subject_id' => $this->selectedSubject,
                    'chapter_id' => $chapterId,
                ],
                [
                    'is_covered'  => (bool) $isCovered,
                    'covered_on'  => $isCovered ? Carbon::today() : null,
                    'covered_by'  => Auth::id(),
                    'notes'       => $this->coverageNotes[$chapterId] ?? null,
                ]
            );
        }

        $this->saved = true;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Syllabus coverage updated.']);
    }

    public function render()
    {
        return view('livewire.teacher.syllabus.coverage-update')
            ->layout('layouts.teacher', ['title' => 'Syllabus Coverage']);
    }
}
