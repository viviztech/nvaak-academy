<?php

namespace App\Livewire\Admin\Exams;

use Livewire\Component;
use App\Models\Exam;
use App\Models\ExamSection;
use App\Models\ExamQuestion;
use App\Models\ExamSeries;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamBuilder extends Component
{
    public int $currentTab = 1;
    public ?int $examId    = null;
    public ?Exam $exam     = null;

    // Tab 1 - Basics
    public string $name           = '';
    public string $code           = '';
    public string $description    = '';
    public string $exam_type      = 'mock_test';
    public string $course_type    = 'neet';
    public string $batch_id       = '';
    public string $subject_id     = '';
    public string $exam_series_id = '';

    // Tab 2 - Schedule
    public string $start_time       = '';
    public string $end_time         = '';
    public string $duration_minutes = '180';
    public string $total_marks      = '720';
    public string $passing_marks    = '360';
    public string $max_attempts     = '1';

    // Tab 3 - Sections
    public array $sections = [];

    // Tab 4 - Questions
    public string $qSubjectFilter   = '';
    public string $qChapterFilter   = '';
    public string $qDifficultyFilter= '';
    public string $qTypeFilter      = '';
    public string $qSearch          = '';
    public array  $selectedSection  = [];
    public array  $addedQuestions   = []; // [question_id => section_index]

    // Tab 5 - Settings
    public bool $negative_marking_enabled  = true;
    public bool $randomize_questions       = false;
    public bool $randomize_options         = false;
    public bool $show_results_immediately  = true;
    public bool $allow_review_after_submit = true;
    public bool $show_correct_answers      = false;
    public bool $prevent_tab_switch        = true;

    public function mount(?int $examId = null): void
    {
        $this->examId = $examId;

        if ($examId) {
            $this->loadExam($examId);
        } else {
            $this->sections = [
                ['name' => 'Section 1', 'total_questions' => 45, 'marks_per_correct' => 4, 'negative_marks' => 1, 'min_questions_to_attempt' => 0],
            ];
        }
    }

    private function loadExam(int $id): void
    {
        $exam = Exam::with(['sections', 'questions'])->findOrFail($id);
        $this->exam = $exam;

        $this->name           = $exam->name;
        $this->code           = $exam->code;
        $this->description    = $exam->description ?? '';
        $this->exam_type      = $exam->exam_type;
        $this->course_type    = $exam->course_type;
        $this->batch_id       = (string) ($exam->batch_id ?? '');
        $this->subject_id     = (string) ($exam->subject_id ?? '');
        $this->exam_series_id = (string) ($exam->exam_series_id ?? '');
        $this->start_time     = $exam->start_time ? $exam->start_time->format('Y-m-d\TH:i') : '';
        $this->end_time       = $exam->end_time ? $exam->end_time->format('Y-m-d\TH:i') : '';
        $this->duration_minutes = (string) $exam->duration_minutes;
        $this->total_marks    = (string) $exam->total_marks;
        $this->passing_marks  = (string) $exam->passing_marks;
        $this->max_attempts   = (string) ($exam->max_attempts ?? 1);
        $this->negative_marking_enabled  = $exam->negative_marking_enabled;
        $this->randomize_questions       = $exam->randomize_questions;
        $this->randomize_options         = $exam->randomize_options;
        $this->show_results_immediately  = $exam->show_results_immediately;
        $this->allow_review_after_submit = $exam->allow_review_after_submit;
        $this->show_correct_answers      = $exam->show_correct_answers;
        $this->prevent_tab_switch        = $exam->prevent_tab_switch;

        $this->sections = $exam->sections->map(fn ($s) => [
            'name'                   => $s->name,
            'total_questions'        => $s->total_questions,
            'marks_per_correct'      => $s->marks_per_correct,
            'negative_marks'         => $s->negative_marks,
            'min_questions_to_attempt'=> $s->min_questions_to_attempt,
        ])->toArray();

        $this->addedQuestions = $exam->questions->pluck('exam_section_id', 'question_id')->toArray();
    }

    public function goToTab(int $tab): void
    {
        if ($tab > $this->currentTab && ! $this->examId) {
            // Must save basics before navigating forward
            if ($this->currentTab === 1) {
                $this->saveBasics(false);
                return;
            }
        }
        $this->currentTab = $tab;
    }

    public function nextTab(): void
    {
        match ($this->currentTab) {
            1 => $this->saveBasics(true),
            2 => $this->saveSchedule(true),
            3 => $this->saveSections(true),
            4 => $this->currentTab = 5,
            5 => $this->saveSettings(),
            default => null,
        };
    }

    public function prevTab(): void
    {
        if ($this->currentTab > 1) {
            $this->currentTab--;
        }
    }

    // Tab 1 - Basics
    protected function basicsRules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50',
            'description' => 'nullable|string',
            'exam_type'   => 'required|in:mock_test,chapter_test,full_length,practice,sectional',
            'course_type' => 'required|in:neet,ias',
            'batch_id'    => 'nullable|exists:batches,id',
            'subject_id'  => 'nullable|exists:subjects,id',
            'exam_series_id' => 'nullable|exists:exam_series,id',
        ];
    }

    public function saveBasics(bool $advance = true): void
    {
        $this->validate($this->basicsRules());

        $data = [
            'name'           => $this->name,
            'code'           => strtoupper($this->code),
            'description'    => $this->description,
            'exam_type'      => $this->exam_type,
            'course_type'    => $this->course_type,
            'batch_id'       => $this->batch_id ?: null,
            'subject_id'     => $this->subject_id ?: null,
            'exam_series_id' => $this->exam_series_id ?: null,
            'status'         => 'draft',
            'created_by'     => Auth::id(),
            'institute_id'   => 1,
        ];

        if ($this->examId) {
            $this->exam->update($data);
        } else {
            $this->exam   = Exam::create($data);
            $this->examId = $this->exam->id;
        }

        if ($advance) {
            $this->currentTab = 2;
        }

        session()->flash('success', 'Basics saved.');
    }

    // Tab 2 - Schedule
    public function saveSchedule(bool $advance = true): void
    {
        $this->validate([
            'duration_minutes' => 'required|integer|min:1',
            'total_marks'      => 'required|integer|min:1',
            'passing_marks'    => 'required|integer|min:0',
            'max_attempts'     => 'required|integer|min:1',
            'start_time'       => 'nullable|date',
            'end_time'         => 'nullable|date|after_or_equal:start_time',
        ]);

        $this->exam->update([
            'start_time'       => $this->start_time ?: null,
            'end_time'         => $this->end_time ?: null,
            'duration_minutes' => $this->duration_minutes,
            'total_marks'      => $this->total_marks,
            'passing_marks'    => $this->passing_marks,
            'max_attempts'     => $this->max_attempts,
        ]);

        if ($advance) {
            $this->currentTab = 3;
        }

        session()->flash('success', 'Schedule saved.');
    }

    // Tab 3 - Sections
    public function addSection(): void
    {
        $this->sections[] = [
            'name'                    => 'Section ' . (count($this->sections) + 1),
            'total_questions'         => 45,
            'marks_per_correct'       => 4,
            'negative_marks'          => 1,
            'min_questions_to_attempt'=> 0,
        ];
    }

    public function removeSection(int $index): void
    {
        unset($this->sections[$index]);
        $this->sections = array_values($this->sections);
    }

    public function saveSections(bool $advance = true): void
    {
        if (! $this->examId) {
            session()->flash('error', 'Please save basics first.');
            return;
        }

        // Delete existing sections and re-create
        ExamSection::where('exam_id', $this->examId)->delete();

        foreach ($this->sections as $i => $section) {
            ExamSection::create([
                'exam_id'                 => $this->examId,
                'name'                    => $section['name'],
                'total_questions'         => $section['total_questions'],
                'marks_per_correct'       => $section['marks_per_correct'],
                'negative_marks'          => $section['negative_marks'],
                'min_questions_to_attempt'=> $section['min_questions_to_attempt'] ?? 0,
                'order'                   => $i + 1,
            ]);
        }

        if ($advance) {
            $this->currentTab = 4;
        }

        session()->flash('success', 'Sections saved.');
    }

    // Tab 4 - Questions
    public function addQuestionToExam(int $questionId, ?int $sectionId = null): void
    {
        if (! $this->examId) {
            session()->flash('error', 'Please save the exam first.');
            return;
        }

        if (ExamQuestion::where('exam_id', $this->examId)->where('question_id', $questionId)->exists()) {
            session()->flash('error', 'This question is already added.');
            return;
        }

        $order = ExamQuestion::where('exam_id', $this->examId)->max('order') + 1;

        $question = Question::findOrFail($questionId);

        ExamQuestion::create([
            'exam_id'        => $this->examId,
            'exam_section_id'=> $sectionId,
            'question_id'    => $questionId,
            'order'          => $order,
            'marks'          => $question->marks,
            'negative_marks' => $question->negative_marks,
        ]);

        $this->addedQuestions[$questionId] = $sectionId;
        session()->flash('success', 'Question added.');
    }

    public function removeQuestionFromExam(int $questionId): void
    {
        ExamQuestion::where('exam_id', $this->examId)
                    ->where('question_id', $questionId)
                    ->delete();
        unset($this->addedQuestions[$questionId]);
    }

    // Tab 5 - Settings
    public function saveSettings(): void
    {
        if (! $this->examId) {
            session()->flash('error', 'Please save basics first.');
            return;
        }

        $this->exam->update([
            'negative_marking_enabled'  => $this->negative_marking_enabled,
            'randomize_questions'       => $this->randomize_questions,
            'randomize_options'         => $this->randomize_options,
            'show_results_immediately'  => $this->show_results_immediately,
            'allow_review_after_submit' => $this->allow_review_after_submit,
            'show_correct_answers'      => $this->show_correct_answers,
            'prevent_tab_switch'        => $this->prevent_tab_switch,
        ]);

        session()->flash('success', 'Settings saved. Exam configuration complete!');
    }

    public function saveDraft(): void
    {
        match ($this->currentTab) {
            1 => $this->saveBasics(false),
            2 => $this->saveSchedule(false),
            3 => $this->saveSections(false),
            5 => $this->saveSettings(),
            default => null,
        };
    }

    public function render()
    {
        $batches     = Batch::active()->orderBy('name')->get();
        $subjects    = Subject::orderBy('name')->get();
        $examSeries  = ExamSeries::active()->orderBy('name')->get();

        $dbSections = $this->examId
            ? ExamSection::where('exam_id', $this->examId)->orderBy('order')->get()
            : collect();

        // For questions tab
        $browseQuestions = [];
        if ($this->currentTab === 4 && $this->examId) {
            $browseQuestions = Question::query()
                ->with(['subject', 'chapter'])
                ->when($this->qSubjectFilter, fn ($q) => $q->where('subject_id', $this->qSubjectFilter))
                ->when($this->qChapterFilter, fn ($q) => $q->where('chapter_id', $this->qChapterFilter))
                ->when($this->qDifficultyFilter, fn ($q) => $q->where('difficulty', $this->qDifficultyFilter))
                ->when($this->qTypeFilter, fn ($q) => $q->where('question_type', $this->qTypeFilter))
                ->when($this->qSearch, fn ($q) => $q->where('question_text', 'like', "%{$this->qSearch}%"))
                ->where('is_active', true)
                ->limit(50)
                ->get();
        }

        $chapters = $this->qSubjectFilter
            ? \App\Models\Chapter::where('subject_id', $this->qSubjectFilter)->orderBy('name')->get()
            : collect();

        $addedQuestionIds = $this->examId
            ? ExamQuestion::where('exam_id', $this->examId)->pluck('question_id')->toArray()
            : [];

        $sectionQuestionCounts = $this->examId
            ? ExamQuestion::where('exam_id', $this->examId)
                          ->selectRaw('exam_section_id, count(*) as total')
                          ->groupBy('exam_section_id')
                          ->pluck('total', 'exam_section_id')
                          ->toArray()
            : [];

        return view('livewire.admin.exams.exam-builder', [
            'batches'               => $batches,
            'subjects'              => $subjects,
            'examSeries'            => $examSeries,
            'dbSections'            => $dbSections,
            'browseQuestions'       => $browseQuestions,
            'chapters'              => $chapters,
            'addedQuestionIds'      => $addedQuestionIds,
            'sectionQuestionCounts' => $sectionQuestionCounts,
        ])->layout('layouts.admin', ['title' => $this->examId ? 'Edit Exam' : 'Create Exam']);
    }
}
