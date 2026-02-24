<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\Subject;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $bankFilter = '';
    public string $subjectFilter = '';
    public string $chapterFilter = '';
    public string $typeFilter = '';
    public string $difficultyFilter = '';
    public string $verifiedFilter = '';
    public int $perPage = 20;
    public bool $showFormModal = false;
    public ?int $editingQuestionId = null;

    protected $listeners = [
        'questionSaved'   => 'onQuestionSaved',
        'closeQuestionForm'=> 'closeModal',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBankFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter(): void
    {
        $this->resetPage();
        $this->chapterFilter = '';
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDifficultyFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->editingQuestionId = null;
        $this->showFormModal = true;
    }

    public function openEditModal(int $questionId): void
    {
        $this->editingQuestionId = $questionId;
        $this->showFormModal = true;
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->editingQuestionId = null;
    }

    public function onQuestionSaved(): void
    {
        $this->closeModal();
        session()->flash('success', 'Question saved successfully.');
    }

    public function toggleVerified(int $questionId): void
    {
        $question = Question::findOrFail($questionId);
        $question->update([
            'is_verified' => ! $question->is_verified,
            'verified_by' => $question->is_verified ? null : Auth::id(),
        ]);
        session()->flash('success', 'Question verification status updated.');
    }

    public function deleteQuestion(int $questionId): void
    {
        Question::findOrFail($questionId)->delete();
        session()->flash('success', 'Question deleted successfully.');
        $this->resetPage();
    }

    public function render()
    {
        $questions = Question::query()
            ->with(['subject', 'chapter', 'questionBank'])
            ->when($this->search, fn ($q) =>
                $q->where('question_text', 'like', "%{$this->search}%")
            )
            ->when($this->bankFilter, fn ($q) =>
                $q->where('question_bank_id', $this->bankFilter)
            )
            ->when($this->subjectFilter, fn ($q) =>
                $q->where('subject_id', $this->subjectFilter)
            )
            ->when($this->chapterFilter, fn ($q) =>
                $q->where('chapter_id', $this->chapterFilter)
            )
            ->when($this->typeFilter, fn ($q) =>
                $q->where('question_type', $this->typeFilter)
            )
            ->when($this->difficultyFilter, fn ($q) =>
                $q->where('difficulty', $this->difficultyFilter)
            )
            ->when($this->verifiedFilter !== '', fn ($q) =>
                $q->where('is_verified', (bool) $this->verifiedFilter)
            )
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $banks    = QuestionBank::active()->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $chapters = $this->subjectFilter
            ? Chapter::where('subject_id', $this->subjectFilter)->orderBy('name')->get()
            : collect();

        $stats = [
            'total'    => Question::count(),
            'verified' => Question::where('is_verified', true)->count(),
            'mcq'      => Question::where('question_type', 'mcq_single')->count(),
            'numerical'=> Question::where('question_type', 'numerical')->count(),
        ];

        return view('livewire.admin.questions.index', [
            'questions' => $questions,
            'banks'     => $banks,
            'subjects'  => $subjects,
            'chapters'  => $chapters,
            'stats'     => $stats,
        ])->layout('layouts.admin', ['title' => 'Question Bank']);
    }
}
