<?php

namespace App\Livewire\Admin\IAS;

use App\Models\IasAnswerEvaluation;
use App\Models\IasAnswerSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EvaluationPanel extends Component
{
    use WithFileUploads;

    public int $submissionId;
    public ?IasAnswerSubmission $submission = null;

    public string $content_score      = '0';
    public string $language_score     = '0';
    public string $structure_score    = '0';
    public string $analytical_score   = '0';
    public string $general_feedback   = '';
    public string $strengths          = '';
    public string $improvements_needed = '';
    public $annotatedFile;
    public $modelAnswerFile;

    protected function rules(): array
    {
        return [
            'content_score'      => 'required|numeric|min:0|max:50',
            'language_score'     => 'required|numeric|min:0|max:50',
            'structure_score'    => 'required|numeric|min:0|max:50',
            'analytical_score'   => 'required|numeric|min:0|max:50',
            'general_feedback'   => 'nullable|string',
            'strengths'          => 'nullable|string',
            'improvements_needed' => 'nullable|string',
            'annotatedFile'      => 'nullable|file|mimes:pdf|max:20480',
            'modelAnswerFile'    => 'nullable|file|mimes:pdf|max:20480',
        ];
    }

    public function mount(int $submissionId): void
    {
        $this->submissionId = $submissionId;
        $this->submission   = IasAnswerSubmission::with([
            'student.user',
            'subject',
            'batch',
            'evaluation.evaluator',
        ])->findOrFail($submissionId);

        // Pre-fill if editing existing evaluation
        if ($this->submission->evaluation) {
            $eval                       = $this->submission->evaluation;
            $this->content_score        = (string) $eval->content_score;
            $this->language_score       = (string) $eval->language_score;
            $this->structure_score      = (string) $eval->structure_score;
            $this->analytical_score     = (string) $eval->analytical_score;
            $this->general_feedback     = $eval->general_feedback ?? '';
            $this->strengths            = $eval->strengths ?? '';
            $this->improvements_needed  = $eval->improvements_needed ?? '';
        }
    }

    public function getTotalMarksProperty(): float
    {
        return (float) $this->content_score
            + (float) $this->language_score
            + (float) $this->structure_score
            + (float) $this->analytical_score;
    }

    public function save(): void
    {
        $this->validate();

        $annotatedPath   = null;
        $modelAnswerPath = null;

        if ($this->annotatedFile) {
            $annotatedPath = $this->annotatedFile->store(
                'ias-evaluations/' . $this->submissionId,
                'public'
            );
        }

        if ($this->modelAnswerFile) {
            $modelAnswerPath = $this->modelAnswerFile->store(
                'ias-model-answers/' . $this->submissionId,
                'public'
            );
        }

        IasAnswerEvaluation::updateOrCreate(
            ['submission_id' => $this->submissionId],
            [
                'evaluator_id'     => Auth::id(),
                'evaluated_at'     => now(),
                'total_marks'      => 200,
                'marks_awarded'    => $this->totalMarks,
                'content_score'    => $this->content_score,
                'language_score'   => $this->language_score,
                'structure_score'  => $this->structure_score,
                'analytical_score' => $this->analytical_score,
                'general_feedback' => $this->general_feedback ?: null,
                'strengths'        => $this->strengths ?: null,
                'improvements_needed' => $this->improvements_needed ?: null,
                'annotated_file_path' => $annotatedPath ?: $this->submission->evaluation?->annotated_file_path,
                'model_answer_path'   => $modelAnswerPath ?: $this->submission->evaluation?->model_answer_path,
            ]
        );

        $this->submission->update(['status' => 'evaluated']);
        $this->submission->refresh()->load(['student.user', 'subject', 'batch', 'evaluation.evaluator']);

        session()->flash('success', 'Evaluation saved successfully.');
    }

    public function returnToStudent(): void
    {
        if (! $this->submission->evaluation) {
            session()->flash('error', 'Please save the evaluation first before returning to student.');

            return;
        }

        $this->submission->evaluation->update([
            'is_returned_to_student' => true,
            'returned_at'            => now(),
        ]);

        $this->submission->update(['status' => 'returned']);
        $this->submission->refresh()->load(['student.user', 'subject', 'batch', 'evaluation.evaluator']);

        session()->flash('success', 'Answer returned to student.');
    }

    public function render()
    {
        return view('livewire.admin.ias.evaluation-panel')
            ->layout('layouts.admin', ['title' => 'Evaluate Answer']);
    }
}
