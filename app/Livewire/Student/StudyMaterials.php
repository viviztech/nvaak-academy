<?php

namespace App\Livewire\Student;

use App\Models\Batch;
use App\Models\Chapter;
use App\Models\MaterialAccessLog;
use App\Models\Student;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudyMaterials extends Component
{
    public string $selectedSubjectId = '';
    public string $selectedChapterId = '';
    public string $typeFilter        = '';
    public string $search            = '';

    protected ?Student $student  = null;
    protected ?Batch $batch      = null;

    public function mount(): void
    {
        $this->student = Student::where('user_id', Auth::id())->with('batches')->first();
        if ($this->student) {
            $this->batch = $this->student->batches()->where('batch_students.status', 'active')->first();
        }
    }

    protected function getStudent(): ?Student
    {
        if (! $this->student) {
            $this->student = Student::where('user_id', Auth::id())->with('batches')->first();
        }

        return $this->student;
    }

    protected function getBatch(): ?Batch
    {
        if (! $this->batch) {
            $student = $this->getStudent();
            if ($student) {
                $this->batch = $student->batches()->where('batch_students.status', 'active')->first();
            }
        }

        return $this->batch;
    }

    public function getSubjectsProperty()
    {
        return Subject::active()->orderBy('name')->get();
    }

    public function getChaptersProperty()
    {
        if (! $this->selectedSubjectId) {
            return collect();
        }

        return Chapter::where('subject_id', $this->selectedSubjectId)->active()->ordered()->get();
    }

    public function getMaterialsProperty()
    {
        $batch = $this->getBatch();

        $query = StudyMaterial::with(['subject', 'chapter'])
            ->published()
            ->when($batch, fn ($q) => $q->forBatch($batch->id))
            ->when($this->selectedSubjectId, fn ($q) => $q->where('subject_id', $this->selectedSubjectId))
            ->when($this->selectedChapterId, fn ($q) => $q->where('chapter_id', $this->selectedChapterId))
            ->when($this->typeFilter, fn ($q) => $q->byType($this->typeFilter))
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->orderBy('title');

        return $query->get();
    }

    public function logAccess(int $materialId): void
    {
        $student = $this->getStudent();
        if (! $student) {
            return;
        }

        StudyMaterial::where('id', $materialId)->increment('view_count');

        MaterialAccessLog::create([
            'material_id'    => $materialId,
            'student_id'     => $student->id,
            'accessed_at'    => now(),
            'completed'      => false,
            'progress_percent' => 0,
            'device_type'    => 'web',
            'ip_address'     => request()->ip(),
        ]);
    }

    public function selectSubject(string $subjectId): void
    {
        $this->selectedSubjectId = $this->selectedSubjectId === $subjectId ? '' : $subjectId;
        $this->selectedChapterId = '';
    }

    public function render()
    {
        return view('livewire.student.study-materials')
            ->layout('layouts.student', ['title' => 'Study Materials']);
    }
}
