<?php

namespace App\Livewire\Student\Exams;

use Livewire\Component;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use App\Models\Question;
use App\Models\Student;
use App\Jobs\ProcessExamResults;
use Illuminate\Support\Facades\Auth;

class TakeExam extends Component
{
    public int $examId;
    public ?Exam $exam                = null;
    public ?StudentExam $studentExam  = null;
    public array $questions           = [];  // indexed by position (0-based)
    public int $currentQuestion       = 0;
    public array $answers             = [];  // [question_id => [answer_values]]
    public array $markedForReview     = [];  // [question_id => bool]
    public array $timeSpent           = [];  // [question_id => seconds]
    public int $totalSeconds          = 0;
    public bool $submitted            = false;
    public string $examStatus         = 'not_started';
    public ?int $studentId            = null;
    public bool $showConfirmSubmit    = false;

    protected $listeners = [
        'tick' => 'onTick',
    ];

    public function mount(int $examId): void
    {
        $this->examId = $examId;
        $this->exam   = Exam::with([
            'questions' => fn ($q) => $q->orderBy('order'),
            'questions.question',
            'sections',
        ])->findOrFail($examId);

        $student = Student::where('user_id', Auth::id())->first();
        if (! $student) {
            abort(403, 'Student profile not found.');
        }
        $this->studentId = $student->id;

        // Check if student has an in-progress attempt
        $existing = StudentExam::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existing) {
            $this->studentExam = $existing;
            $this->examStatus  = 'started';
            $this->loadQuestions();
            $this->loadSavedAnswers();
        }
    }

    private function loadQuestions(): void
    {
        $eqs = $this->exam->questions;

        if ($this->exam->randomize_questions) {
            $seed = $this->studentExam->id ?? 0;
            srand($seed);
            $eqs = $eqs->shuffle();
        }

        $this->questions = $eqs->values()->map(fn ($eq) => [
            'position'       => $eq->order,
            'question_id'    => $eq->question_id,
            'section_id'     => $eq->exam_section_id,
            'marks'          => $eq->marks,
            'negative_marks' => $eq->negative_marks,
            'question'       => $eq->question->toArray(),
        ])->toArray();
    }

    private function loadSavedAnswers(): void
    {
        if (! $this->studentExam) return;

        $savedAnswers = StudentAnswer::where('student_exam_id', $this->studentExam->id)->get();

        foreach ($savedAnswers as $sa) {
            $this->answers[$sa->question_id]          = $sa->given_answer ?? [];
            $this->markedForReview[$sa->question_id]  = $sa->is_marked_for_review ?? false;
            $this->timeSpent[$sa->question_id]        = $sa->time_spent_seconds ?? 0;
        }
    }

    public function startExam(): void
    {
        if ($this->examStatus !== 'not_started') return;

        // Verify exam is live
        if (! in_array($this->exam->status, ['live', 'published'])) {
            session()->flash('error', 'This exam is not available.');
            return;
        }

        // Check max attempts
        $attemptCount = StudentExam::where('exam_id', $this->examId)
            ->where('student_id', $this->studentId)
            ->count();

        if ($attemptCount >= ($this->exam->max_attempts ?? 1)) {
            session()->flash('error', 'You have used all your attempts for this exam.');
            return;
        }

        $student = Student::find($this->studentId);
        $batchId = $student->batches()->first()?->id;

        $this->studentExam = StudentExam::create([
            'exam_id'        => $this->examId,
            'student_id'     => $this->studentId,
            'batch_id'       => $batchId,
            'attempt_number' => $attemptCount + 1,
            'status'         => 'in_progress',
            'started_at'     => now(),
            'ip_address'     => request()->ip(),
        ]);

        $this->examStatus = 'started';
        $this->totalSeconds = ($this->exam->duration_minutes ?? 180) * 60;
        $this->loadQuestions();
        $this->dispatch('examStarted', remainingSeconds: $this->totalSeconds);
    }

    public function saveAnswer(int $questionId, mixed $answer): void
    {
        if (! $this->studentExam || $this->submitted) return;

        $answerArray = is_array($answer) ? $answer : [$answer];
        $this->answers[$questionId] = $answerArray;

        StudentAnswer::updateOrCreate(
            [
                'student_exam_id' => $this->studentExam->id,
                'question_id'     => $questionId,
            ],
            [
                'given_answer'        => $answerArray,
                'is_skipped'          => false,
                'is_marked_for_review'=> $this->markedForReview[$questionId] ?? false,
                'time_spent_seconds'  => $this->timeSpent[$questionId] ?? 0,
            ]
        );
    }

    public function clearAnswer(int $questionId): void
    {
        unset($this->answers[$questionId]);
        StudentAnswer::updateOrCreate(
            [
                'student_exam_id' => $this->studentExam->id,
                'question_id'     => $questionId,
            ],
            [
                'given_answer' => null,
                'is_skipped'   => true,
            ]
        );
    }

    public function toggleMarkForReview(int $questionId): void
    {
        $current = $this->markedForReview[$questionId] ?? false;
        $this->markedForReview[$questionId] = ! $current;

        if ($this->studentExam) {
            StudentAnswer::updateOrCreate(
                [
                    'student_exam_id' => $this->studentExam->id,
                    'question_id'     => $questionId,
                ],
                [
                    'is_marked_for_review' => ! $current,
                ]
            );
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestion = $index;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestion < count($this->questions) - 1) {
            $this->currentQuestion++;
        }
    }

    public function prevQuestion(): void
    {
        if ($this->currentQuestion > 0) {
            $this->currentQuestion--;
        }
    }

    public function confirmSubmit(): void
    {
        $this->showConfirmSubmit = true;
    }

    public function cancelSubmit(): void
    {
        $this->showConfirmSubmit = false;
    }

    public function submitExam(): void
    {
        if ($this->submitted || ! $this->studentExam) return;

        // Mark any unanswered questions as skipped
        foreach ($this->questions as $q) {
            $qId = $q['question_id'];
            if (! isset($this->answers[$qId])) {
                StudentAnswer::updateOrCreate(
                    [
                        'student_exam_id' => $this->studentExam->id,
                        'question_id'     => $qId,
                    ],
                    ['is_skipped' => true, 'given_answer' => null]
                );
            }
        }

        $timeTaken = ($this->exam->duration_minutes * 60) - $this->totalSeconds;

        $this->studentExam->update([
            'status'             => 'submitted',
            'submitted_at'       => now(),
            'time_taken_seconds' => max(0, $timeTaken),
        ]);

        $this->submitted   = true;
        $this->examStatus  = 'submitted';
        $this->showConfirmSubmit = false;

        // Dispatch grading job
        ProcessExamResults::dispatch($this->studentExam->id);

        $this->dispatch('examSubmitted');
        $this->redirect(route('student.exams.result', ['studentExamId' => $this->studentExam->id]));
    }

    public function autoSave(): void
    {
        // Called by Livewire polling every 30s - answers are already saved on each change
        // Just update the time taken
        if ($this->studentExam && ! $this->submitted) {
            $elapsed = ($this->exam->duration_minutes * 60) - $this->totalSeconds;
            $this->studentExam->update(['time_taken_seconds' => max(0, $elapsed)]);
        }
    }

    public function getNavigatorStatusProperty(): array
    {
        $statuses = [];
        foreach ($this->questions as $index => $q) {
            $qId = $q['question_id'];
            if ($index === $this->currentQuestion) {
                $status = 'current';
            } elseif (isset($this->markedForReview[$qId]) && $this->markedForReview[$qId]) {
                $status = isset($this->answers[$qId]) && count($this->answers[$qId]) > 0
                    ? 'review_answered'
                    : 'review';
            } elseif (isset($this->answers[$qId]) && count($this->answers[$qId]) > 0) {
                $status = 'answered';
            } else {
                $status = 'not_visited';
            }
            $statuses[$index] = $status;
        }
        return $statuses;
    }

    public function render()
    {
        $currentQ = $this->questions[$this->currentQuestion] ?? null;
        $question = null;
        if ($currentQ) {
            $question = Question::find($currentQ['question_id']);
            if ($this->exam->randomize_options && $question && in_array($question->question_type, ['mcq_single', 'mcq_multiple'])) {
                // Shuffle options for display (not implementation here for brevity)
            }
        }

        $navigatorStatus = $this->navigatorStatus;

        $answeredCount = count(array_filter($this->answers, fn ($a) => count($a) > 0));
        $reviewCount   = count(array_filter($this->markedForReview, fn ($v) => $v === true));

        return view('livewire.student.exams.take-exam', [
            'question'       => $question,
            'currentQ'       => $currentQ,
            'navigatorStatus'=> $navigatorStatus,
            'answeredCount'  => $answeredCount,
            'reviewCount'    => $reviewCount,
        ])->layout('layouts.student', ['title' => $this->exam->name ?? 'Exam']);
    }
}
