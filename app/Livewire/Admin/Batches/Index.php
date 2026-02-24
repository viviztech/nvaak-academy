<?php

namespace App\Livewire\Admin\Batches;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Batch;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $courseFilter = '';
    public string $batchTypeFilter = '';
    public bool $showCreateModal = false;

    // Create form fields
    public string $name = '';
    public string $code = '';
    public string $course_type = 'neet';
    public string $batch_type = 'foundation';
    public string $academic_year = '';
    public string $medium = 'english';
    public int $max_strength = 60;
    public string $start_date = '';
    public string $end_date = '';
    public string $class_room = '';
    public string $description = '';

    protected $rules = [
        'name'          => 'required|string|max:255',
        'code'          => 'required|string|max:50|unique:batches,code',
        'course_type'   => 'required|in:neet,ias',
        'batch_type'    => 'required|in:foundation,target,crash_course,repeater,weekend,prelims,mains,integrated',
        'academic_year' => 'required|string',
        'medium'        => 'required|in:english,tamil,bilingual',
        'max_strength'  => 'required|integer|min:1|max:200',
        'start_date'    => 'nullable|date',
        'end_date'      => 'nullable|date|after_or_equal:start_date',
        'class_room'    => 'nullable|string|max:100',
        'description'   => 'nullable|string|max:1000',
    ];

    protected function messages(): array
    {
        return [
            'code.unique' => 'This batch code is already taken. Please choose a different one.',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCourseFilter(): void
    {
        $this->resetPage();
    }

    public function updatingBatchTypeFilter(): void
    {
        $this->resetPage();
    }

    public function getBatches()
    {
        return Batch::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->courseFilter, fn($q) => $q->where('course_type', $this->courseFilter))
            ->when($this->batchTypeFilter, fn($q) => $q->where('batch_type', $this->batchTypeFilter))
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function saveBatch(): void
    {
        $this->validate();

        $institute = \App\Models\Institute::where('slug', 'nvaak-academy')->first();

        Batch::create([
            'institute_id'  => $institute ? $institute->id : 1,
            'name'          => $this->name,
            'code'          => strtoupper($this->code),
            'course_type'   => $this->course_type,
            'batch_type'    => $this->batch_type,
            'academic_year' => $this->academic_year,
            'medium'        => $this->medium,
            'max_strength'  => $this->max_strength,
            'start_date'    => $this->start_date ?: null,
            'end_date'      => $this->end_date ?: null,
            'class_room'    => $this->class_room,
            'description'   => $this->description,
            'is_active'     => true,
        ]);

        $this->reset([
            'name', 'code', 'course_type', 'batch_type', 'academic_year',
            'medium', 'max_strength', 'start_date', 'end_date',
            'class_room', 'description', 'showCreateModal',
        ]);

        $this->course_type = 'neet';
        $this->batch_type  = 'foundation';
        $this->medium      = 'english';
        $this->max_strength = 60;

        session()->flash('success', 'Batch created successfully!');
        $this->resetPage();
    }

    public function toggleActive(int $batchId): void
    {
        $batch = Batch::findOrFail($batchId);
        $batch->update(['is_active' => !$batch->is_active]);
        session()->flash('success', 'Batch status updated.');
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset([
            'name', 'code', 'academic_year', 'start_date', 'end_date',
            'class_room', 'description',
        ]);
        $this->course_type  = 'neet';
        $this->batch_type   = 'foundation';
        $this->medium       = 'english';
        $this->max_strength = 60;
        $this->showCreateModal = true;
    }

    public function closeModal(): void
    {
        $this->showCreateModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        $batches = $this->getBatches();

        $activeNeet = Batch::where('course_type', 'neet')->where('is_active', true)->count();
        $activeIas  = Batch::where('course_type', 'ias')->where('is_active', true)->count();
        $totalStudents = \App\Models\Student::count();

        return view('livewire.admin.batches.index', [
            'batches'       => $batches,
            'activeNeet'    => $activeNeet,
            'activeIas'     => $activeIas,
            'totalStudents' => $totalStudents,
        ])->layout('layouts.admin', ['title' => 'Batches']);
    }
}
