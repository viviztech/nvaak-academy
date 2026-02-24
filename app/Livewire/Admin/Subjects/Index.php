<?php

namespace App\Livewire\Admin\Subjects;

use Livewire\Component;
use App\Models\Subject;
use App\Models\Chapter;

class Index extends Component
{
    public string $search = '';
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $code = '';
    public string $targetExam = 'neet';
    public string $description = '';

    // Chapter panel
    public ?int $selectedSubjectId = null;
    public string $chapterName = '';
    public int $chapterOrder = 1;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $subject = Subject::findOrFail($id);
        $this->editingId    = $id;
        $this->name         = $subject->name;
        $this->code         = $subject->code ?? '';
        $this->targetExam   = $subject->target_exam ?? 'neet';
        $this->description  = $subject->description ?? '';
        $this->showModal    = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'targetExam' => 'required|in:neet,ias,both',
        ]);

        Subject::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'        => $this->name,
                'code'        => $this->code ?: null,
                'target_exam' => $this->targetExam,
                'description' => $this->description ?: null,
                'institute_id' => auth()->user()->institute_id,
            ]
        );

        session()->flash('success', $this->editingId ? 'Subject updated.' : 'Subject created.');
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Subject::findOrFail($id)->delete();
        session()->flash('success', 'Subject deleted.');
        $this->selectedSubjectId = null;
    }

    public function selectSubject(int $id): void
    {
        $this->selectedSubjectId = ($this->selectedSubjectId === $id) ? null : $id;
    }

    public function addChapter(): void
    {
        $this->validate(['chapterName' => 'required|string|max:255']);
        Chapter::create([
            'subject_id'    => $this->selectedSubjectId,
            'name'          => $this->chapterName,
            'chapter_order' => $this->chapterOrder,
        ]);
        $this->chapterName = '';
        $this->chapterOrder++;
    }

    public function deleteChapter(int $id): void
    {
        Chapter::findOrFail($id)->delete();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = $this->code = $this->description = '';
        $this->targetExam = 'neet';
    }

    public function render()
    {
        $subjects = Subject::withCount('chapters')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->get();

        $selectedSubject = $this->selectedSubjectId
            ? Subject::with('chapters')->find($this->selectedSubjectId)
            : null;

        return view('livewire.admin.subjects.index', [
            'subjects'        => $subjects,
            'selectedSubject' => $selectedSubject,
        ])->layout('layouts.admin', ['title' => 'Subjects & Chapters']);
    }
}
