<?php

namespace App\Livewire\Teacher\IAS;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\IASSubmission;
use App\Models\IASEvaluation;
use Illuminate\Support\Facades\Auth;

class AnswerEvaluator extends Component
{
    use WithPagination;

    // Submission list filters
    public $statusFilter  = 'pending';
    public $search        = '';

    // Evaluation form (shown when a submission is selected)
    public $selectedSubmission = null;
    public $showEvalForm       = false;

    // Scoring fields (max 100 total)
    public $score_content    = 0;   // max 40
    public $score_language   = 0;   // max 20
    public $score_structure  = 0;   // max 20
    public $score_analytical = 0;   // max 20

    public $general_feedback    = '';
    public $strengths           = '';
    public $improvements_needed = '';
    public $evaluated_grade     = '';

    protected $rules = [
        'score_content'       => 'required|integer|min:0|max:40',
        'score_language'      => 'required|integer|min:0|max:20',
        'score_structure'     => 'required|integer|min:0|max:20',
        'score_analytical'    => 'required|integer|min:0|max:20',
        'general_feedback'    => 'required|string|min:10',
        'strengths'           => 'nullable|string',
        'improvements_needed' => 'nullable|string',
    ];

    public function openEvaluation(int $submissionId)
    {
        $this->selectedSubmission = IASSubmission::with(['student', 'subject'])->findOrFail($submissionId);

        // Pre-fill if already evaluated
        $existing = IASEvaluation::where('submission_id', $submissionId)->first();
        if ($existing) {
            $this->score_content       = $existing->score_content;
            $this->score_language      = $existing->score_language;
            $this->score_structure     = $existing->score_structure;
            $this->score_analytical    = $existing->score_analytical;
            $this->general_feedback    = $existing->general_feedback;
            $this->strengths           = $existing->strengths;
            $this->improvements_needed = $existing->improvements_needed;
            $this->evaluated_grade     = $existing->grade ?? '';
        } else {
            $this->resetEvalForm();
        }

        $this->showEvalForm = true;
    }

    public function closeEvaluation()
    {
        $this->showEvalForm       = false;
        $this->selectedSubmission = null;
        $this->resetEvalForm();
    }

    protected function resetEvalForm()
    {
        $this->score_content       = 0;
        $this->score_language      = 0;
        $this->score_structure     = 0;
        $this->score_analytical    = 0;
        $this->general_feedback    = '';
        $this->strengths           = '';
        $this->improvements_needed = '';
        $this->evaluated_grade     = '';
    }

    public function getTotalScoreProperty(): int
    {
        return (int) $this->score_content
            + (int) $this->score_language
            + (int) $this->score_structure
            + (int) $this->score_analytical;
    }

    public function saveEvaluation()
    {
        $this->validate();

        if (!$this->selectedSubmission) {
            return;
        }

        $totalScore = $this->totalScore;

        // Derive grade
        $grade = match (true) {
            $totalScore >= 90 => 'A+',
            $totalScore >= 80 => 'A',
            $totalScore >= 70 => 'B+',
            $totalScore >= 60 => 'B',
            $totalScore >= 50 => 'C',
            default           => 'D',
        };

        IASEvaluation::updateOrCreate(
            ['submission_id' => $this->selectedSubmission->id],
            [
                'evaluator_id'       => Auth::id(),
                'score_content'      => $this->score_content,
                'score_language'     => $this->score_language,
                'score_structure'    => $this->score_structure,
                'score_analytical'   => $this->score_analytical,
                'total_score'        => $totalScore,
                'grade'              => $grade,
                'general_feedback'   => $this->general_feedback,
                'strengths'          => $this->strengths,
                'improvements_needed' => $this->improvements_needed,
                'evaluated_at'       => now(),
            ]
        );

        // Mark submission as evaluated
        $this->selectedSubmission->update(['status' => 'evaluated']);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Evaluation saved successfully.']);
        $this->closeEvaluation();
    }

    public function render()
    {
        $user   = Auth::user();
        $faculty = $user->faculty ?? null;

        $submissions = IASSubmission::with(['student', 'subject', 'evaluation'])
            ->where('evaluator_id', $faculty?->id ?? Auth::id())
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, fn ($q) => $q->whereHas('student', fn ($sq) =>
                $sq->where('name', 'like', '%' . $this->search . '%')
            ))
            ->orderByDesc('submitted_at')
            ->paginate(15);

        return view('livewire.teacher.ias.answer-evaluator', compact('submissions'))
            ->layout('layouts.teacher', ['title' => 'IAS Evaluation']);
    }
}
