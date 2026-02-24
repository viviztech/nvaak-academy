<?php

namespace App\Livewire\Admin\Batches;

use Livewire\Component;
use App\Models\Batch;
use App\Models\BatchFaculty;
use App\Models\Faculty;
use App\Models\Subject;

class FacultyAssignment extends Component
{
    public ?int $batch_id = null;
    public ?int $faculty_id = null;
    public ?int $subject_id = null;
    public bool $is_primary = false;

    public function mount(Batch $batch): void
    {
        $this->batch_id = $batch->id;
    }

    protected $rules = [
        'batch_id'   => 'required|exists:batches,id',
        'faculty_id' => 'required|exists:faculty,id',
        'subject_id' => 'required|exists:subjects,id',
        'is_primary' => 'boolean',
    ];

    public function getBatchesProperty()
    {
        return Batch::where('is_active', true)->orderBy('name')->get();
    }

    public function getFacultyListProperty()
    {
        return Faculty::with('user')->get();
    }

    public function getSubjectsProperty()
    {
        if (!$this->batch_id) {
            return collect();
        }
        $batch = Batch::find($this->batch_id);
        if (!$batch) {
            return collect();
        }
        return Subject::where('course_type', $this->batch->course_type)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get();
    }

    public function getBatchProperty()
    {
        if (!$this->batch_id) {
            return null;
        }
        return Batch::find($this->batch_id);
    }

    public function getExistingAssignmentsProperty()
    {
        if (!$this->batch_id) {
            return collect();
        }
        return BatchFaculty::where('batch_id', $this->batch_id)
            ->with(['faculty.user', 'subject'])
            ->get();
    }

    public function save(): void
    {
        $this->validate();

        $alreadyAssigned = BatchFaculty::where('batch_id', $this->batch_id)
            ->where('faculty_id', $this->faculty_id)
            ->where('subject_id', $this->subject_id)
            ->exists();

        if ($alreadyAssigned) {
            $this->addError('faculty_id', 'This faculty is already assigned to this batch for the selected subject.');
            return;
        }

        if ($this->is_primary) {
            BatchFaculty::where('batch_id', $this->batch_id)
                ->where('subject_id', $this->subject_id)
                ->update(['is_primary' => false]);
        }

        BatchFaculty::create([
            'batch_id'    => $this->batch_id,
            'faculty_id'  => $this->faculty_id,
            'subject_id'  => $this->subject_id,
            'is_primary'  => $this->is_primary,
            'assigned_at' => now(),
        ]);

        $this->reset(['faculty_id', 'subject_id', 'is_primary']);
        session()->flash('success', 'Faculty assigned successfully!');
    }

    public function removeAssignment(int $assignmentId): void
    {
        if ($this->batch_id) {
            BatchFaculty::where('id', $assignmentId)
                ->where('batch_id', $this->batch_id)
                ->delete();
            session()->flash('success', 'Assignment removed.');
        }
    }

    public function render()
    {
        return view('livewire.admin.batches.faculty-assignment')
            ->layout('layouts.admin', ['title' => 'Faculty Assignment']);
    }
}
