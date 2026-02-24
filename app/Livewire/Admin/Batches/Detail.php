<?php

namespace App\Livewire\Admin\Batches;

use Livewire\Component;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\BatchFaculty;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Subject;

class Detail extends Component
{
    public Batch $batch;

    // Modal toggles
    public bool $showAddStudentModal = false;
    public bool $showAddFacultyModal = false;

    // Add Student form
    public string $studentSearch = '';
    public ?int $student_id = null;
    public string $roll_number = '';
    public string $student_enrolled_at = '';

    // Add Faculty form
    public ?int $faculty_id = null;
    public ?int $subject_id = null;
    public bool $is_primary = false;

    protected function rules(): array
    {
        return [
            'student_id'          => 'required|exists:students,id',
            'roll_number'         => 'nullable|string|max:20',
            'student_enrolled_at' => 'nullable|date',
            'faculty_id'          => 'required|exists:faculty,id',
            'subject_id'          => 'required|exists:subjects,id',
            'is_primary'          => 'boolean',
        ];
    }

    public function mount(Batch $batch): void
    {
        $this->batch = $batch;
        $this->student_enrolled_at = now()->toDateString();
    }

    public function getStudentsProperty()
    {
        return $this->batch->load(['batchStudents.student.user'])->batchStudents;
    }

    public function getFacultyListProperty()
    {
        return $this->batch->load(['batchFaculty.faculty.user', 'batchFaculty.subject'])->batchFaculty;
    }

    public function getAvailableStudentsProperty()
    {
        $enrolledIds = BatchStudent::where('batch_id', $this->batch->id)->pluck('student_id');
        return Student::with('user')
            ->whereNotIn('id', $enrolledIds)
            ->when($this->studentSearch, fn($q) => $q->whereHas('user', fn($uq) =>
                $uq->where('name', 'like', "%{$this->studentSearch}%")))
            ->limit(20)
            ->get();
    }

    public function getAvailableFacultyProperty()
    {
        $assignedIds = BatchFaculty::where('batch_id', $this->batch->id)->pluck('faculty_id');
        return Faculty::with('user')->whereNotIn('id', $assignedIds)->get();
    }

    public function getSubjectsProperty()
    {
        return Subject::where('course_type', $this->batch->course_type)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    public function openAddStudentModal(): void
    {
        $this->resetValidation();
        $this->student_id          = null;
        $this->roll_number         = '';
        $this->studentSearch       = '';
        $this->student_enrolled_at = now()->toDateString();
        $this->showAddStudentModal = true;
    }

    public function openAddFacultyModal(): void
    {
        $this->resetValidation();
        $this->faculty_id          = null;
        $this->subject_id          = null;
        $this->is_primary          = false;
        $this->showAddFacultyModal = true;
    }

    public function addStudent(): void
    {
        $this->validate([
            'student_id'          => 'required|exists:students,id',
            'roll_number'         => 'nullable|string|max:20',
            'student_enrolled_at' => 'nullable|date',
        ]);

        $alreadyEnrolled = BatchStudent::where('batch_id', $this->batch->id)
            ->where('student_id', $this->student_id)
            ->exists();

        if ($alreadyEnrolled) {
            $this->addError('student_id', 'This student is already enrolled in this batch.');
            return;
        }

        $currentCount = $this->batch->students()->count();
        if ($currentCount >= $this->batch->max_strength) {
            $this->addError('student_id', 'This batch has reached its maximum strength of ' . $this->batch->max_strength . ' students.');
            return;
        }

        BatchStudent::create([
            'batch_id'    => $this->batch->id,
            'student_id'  => $this->student_id,
            'roll_number' => $this->roll_number ?: null,
            'enrolled_at' => $this->student_enrolled_at ?: now(),
            'is_active'   => true,
        ]);

        $this->showAddStudentModal = false;
        $this->reset(['student_id', 'roll_number', 'studentSearch']);
        session()->flash('success', 'Student added to batch successfully.');
    }

    public function removeStudent(int $batchStudentId): void
    {
        BatchStudent::where('id', $batchStudentId)
            ->where('batch_id', $this->batch->id)
            ->delete();
        session()->flash('success', 'Student removed from batch.');
    }

    public function addFaculty(): void
    {
        $this->validate([
            'faculty_id' => 'required|exists:faculty,id',
            'subject_id' => 'required|exists:subjects,id',
            'is_primary' => 'boolean',
        ]);

        $alreadyAssigned = BatchFaculty::where('batch_id', $this->batch->id)
            ->where('faculty_id', $this->faculty_id)
            ->where('subject_id', $this->subject_id)
            ->exists();

        if ($alreadyAssigned) {
            $this->addError('faculty_id', 'This faculty is already assigned to this batch for the selected subject.');
            return;
        }

        if ($this->is_primary) {
            BatchFaculty::where('batch_id', $this->batch->id)
                ->where('subject_id', $this->subject_id)
                ->update(['is_primary' => false]);
        }

        BatchFaculty::create([
            'batch_id'   => $this->batch->id,
            'faculty_id' => $this->faculty_id,
            'subject_id' => $this->subject_id,
            'is_primary' => $this->is_primary,
            'assigned_at' => now(),
        ]);

        $this->showAddFacultyModal = false;
        $this->reset(['faculty_id', 'subject_id', 'is_primary']);
        session()->flash('success', 'Faculty assigned to batch successfully.');
    }

    public function removeFaculty(int $batchFacultyId): void
    {
        BatchFaculty::where('id', $batchFacultyId)
            ->where('batch_id', $this->batch->id)
            ->delete();
        session()->flash('success', 'Faculty removed from batch.');
    }

    public function render()
    {
        return view('livewire.admin.batches.detail')
            ->layout('layouts.admin', ['title' => 'Batch: ' . $this->batch->name]);
    }
}
