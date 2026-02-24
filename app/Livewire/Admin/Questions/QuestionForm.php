<?php

namespace App\Livewire\Admin\Questions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class QuestionForm extends Component
{
    use WithFileUploads;

    public ?int $questionId = null;

    // Classification
    public string $question_bank_id = '';
    public string $subject_id       = '';
    public string $chapter_id       = '';
    public string $topic_id         = '';

    // Core fields
    public string $question_type = 'mcq_single';
    public string $question_text = '';
    public string $explanation   = '';
    public string $difficulty    = 'medium';
    public string $marks         = '4';
    public string $negative_marks= '1';
    public string $year_asked    = '';
    public string $source        = '';
    public string $tags_input    = '';

    // MCQ options
    public array $options = [
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
    ];

    // Correct answer(s) — array to support MCQ multiple
    public array $correct_answer = [];

    // Numerical range
    public string $answer_range_from = '';
    public string $answer_range_to   = '';

    // Match the following
    public array $match_left  = ['', '', '', ''];
    public array $match_right = ['', '', '', ''];
    public array $match_correct= []; // e.g. ['A'=>'3','B'=>'1',...]

    // Assertion-Reason
    public string $assertion        = '';
    public string $reason           = '';
    public string $ar_correct_option= '';

    // True/False
    public string $tf_correct = '';

    protected function rules(): array
    {
        $base = [
            'question_bank_id' => 'nullable|exists:question_banks,id',
            'subject_id'       => 'required|exists:subjects,id',
            'chapter_id'       => 'nullable|exists:chapters,id',
            'topic_id'         => 'nullable|exists:topics,id',
            'question_type'    => 'required|in:mcq_single,mcq_multiple,numerical,match_following,assertion_reason,true_false',
            'question_text'    => 'required|string|min:5',
            'explanation'      => 'nullable|string',
            'difficulty'       => 'required|in:easy,medium,hard,very_hard',
            'marks'            => 'required|numeric|min:0',
            'negative_marks'   => 'required|numeric|min:0',
            'year_asked'       => 'nullable|integer|min:1990|max:' . now()->year,
            'source'           => 'nullable|string|max:255',
        ];

        return match ($this->question_type) {
            'mcq_single', 'mcq_multiple' => array_merge($base, [
                'options.A'    => 'required|string',
                'options.B'    => 'required|string',
                'options.C'    => 'required|string',
                'options.D'    => 'required|string',
                'correct_answer' => 'required|array|min:1',
            ]),
            'numerical' => array_merge($base, [
                'answer_range_from' => 'required|numeric',
                'answer_range_to'   => 'required|numeric|gte:answer_range_from',
            ]),
            'assertion_reason' => array_merge($base, [
                'assertion'         => 'required|string',
                'reason'            => 'required|string',
                'ar_correct_option' => 'required|in:A,B,C,D',
            ]),
            'true_false' => array_merge($base, [
                'tf_correct' => 'required|in:true,false',
            ]),
            default => $base,
        };
    }

    public function mount(?int $questionId = null): void
    {
        $this->questionId = $questionId;

        if ($questionId) {
            $this->loadQuestion($questionId);
        }
    }

    private function loadQuestion(int $id): void
    {
        $q = Question::findOrFail($id);

        $this->question_bank_id = (string) ($q->question_bank_id ?? '');
        $this->subject_id       = (string) ($q->subject_id ?? '');
        $this->chapter_id       = (string) ($q->chapter_id ?? '');
        $this->topic_id         = (string) ($q->topic_id ?? '');
        $this->question_type    = $q->question_type;
        $this->question_text    = $q->question_text;
        $this->explanation      = $q->explanation ?? '';
        $this->difficulty       = $q->difficulty;
        $this->marks            = (string) $q->marks;
        $this->negative_marks   = (string) $q->negative_marks;
        $this->year_asked       = (string) ($q->year_asked ?? '');
        $this->source           = $q->source ?? '';
        $this->tags_input       = implode(', ', $q->tags ?? []);
        $this->correct_answer   = (array) ($q->correct_answer ?? []);

        $opts = $q->options ?? [];
        if (isset($opts['A'])) {
            $this->options = array_merge(['A' => '', 'B' => '', 'C' => '', 'D' => ''], $opts);
        }

        $this->answer_range_from = (string) ($q->answer_range_from ?? '');
        $this->answer_range_to   = (string) ($q->answer_range_to ?? '');

        if ($q->question_type === 'assertion_reason') {
            $this->assertion        = $opts['assertion'] ?? '';
            $this->reason           = $opts['reason'] ?? '';
            $this->ar_correct_option= $this->correct_answer[0] ?? '';
        }

        if ($q->question_type === 'true_false') {
            $this->tf_correct = $this->correct_answer[0] ?? '';
        }

        if ($q->question_type === 'match_following') {
            $this->match_left  = $opts['left']  ?? ['', '', '', ''];
            $this->match_right = $opts['right'] ?? ['', '', '', ''];
            $this->match_correct = $opts['match'] ?? [];
        }
    }

    public function updatedSubjectId(): void
    {
        $this->chapter_id = '';
        $this->topic_id   = '';
    }

    public function updatedChapterId(): void
    {
        $this->topic_id = '';
    }

    public function updatedQuestionType(): void
    {
        // Reset type-specific fields
        $this->correct_answer    = [];
        $this->answer_range_from = '';
        $this->answer_range_to   = '';
        $this->assertion         = '';
        $this->reason            = '';
        $this->ar_correct_option = '';
        $this->tf_correct        = '';
    }

    public function toggleMcqMultipleAnswer(string $option): void
    {
        if (in_array($option, $this->correct_answer)) {
            $this->correct_answer = array_values(
                array_filter($this->correct_answer, fn ($v) => $v !== $option)
            );
        } else {
            $this->correct_answer[] = $option;
        }
    }

    public function save(): void
    {
        $validated = $this->validate();

        // Build options payload
        $optionsPayload = match ($this->question_type) {
            'mcq_single', 'mcq_multiple' => $this->options,
            'assertion_reason'           => [
                'A'         => 'Both Assertion and Reason are true and Reason is correct explanation',
                'B'         => 'Both Assertion and Reason are true but Reason is NOT the correct explanation',
                'C'         => 'Assertion is true but Reason is false',
                'D'         => 'Assertion is false but Reason is true',
                'assertion' => $this->assertion,
                'reason'    => $this->reason,
            ],
            'match_following' => [
                'left'  => $this->match_left,
                'right' => $this->match_right,
                'match' => $this->match_correct,
            ],
            'true_false' => ['true' => 'True', 'false' => 'False'],
            default      => [],
        };

        // Build correct_answer
        $correctAnswer = match ($this->question_type) {
            'mcq_single'      => [$this->correct_answer[0] ?? ''],
            'mcq_multiple'    => $this->correct_answer,
            'assertion_reason'=> [$this->ar_correct_option],
            'true_false'      => [$this->tf_correct],
            'numerical'       => [],
            'match_following' => array_values($this->match_correct),
            default           => [],
        };

        $tags = array_filter(array_map('trim', explode(',', $this->tags_input)));

        $data = [
            'question_bank_id'  => $this->question_bank_id ?: null,
            'subject_id'        => $this->subject_id,
            'chapter_id'        => $this->chapter_id ?: null,
            'topic_id'          => $this->topic_id ?: null,
            'question_type'     => $this->question_type,
            'question_text'     => $this->question_text,
            'options'           => $optionsPayload,
            'correct_answer'    => $correctAnswer,
            'answer_range_from' => $this->question_type === 'numerical' ? $this->answer_range_from : null,
            'answer_range_to'   => $this->question_type === 'numerical' ? $this->answer_range_to : null,
            'explanation'       => $this->explanation,
            'difficulty'        => $this->difficulty,
            'marks'             => $this->marks,
            'negative_marks'    => $this->negative_marks,
            'tags'              => array_values($tags),
            'year_asked'        => $this->year_asked ?: null,
            'source'            => $this->source,
            'is_active'         => true,
            'created_by'        => Auth::id(),
        ];

        if ($this->questionId) {
            Question::findOrFail($this->questionId)->update($data);
        } else {
            Question::create($data);
        }

        $this->dispatch('questionSaved');
    }

    public function cancel(): void
    {
        $this->dispatch('closeQuestionForm');
    }

    public function render()
    {
        $banks    = QuestionBank::active()->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $chapters = $this->subject_id
            ? Chapter::where('subject_id', $this->subject_id)->orderBy('name')->get()
            : collect();
        $topics = $this->chapter_id
            ? Topic::where('chapter_id', $this->chapter_id)->orderBy('name')->get()
            : collect();

        return view('livewire.admin.questions.question-form', [
            'banks'    => $banks,
            'subjects' => $subjects,
            'chapters' => $chapters,
            'topics'   => $topics,
        ]);
    }
}
