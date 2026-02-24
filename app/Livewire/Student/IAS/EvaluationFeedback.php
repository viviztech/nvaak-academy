<?php

namespace App\Livewire\Student\IAS;

use Livewire\Component;
use App\Models\IASSubmission;
use App\Models\IASEvaluation;
use Illuminate\Support\Facades\Auth;

class EvaluationFeedback extends Component
{
    public $submissionId;
    public $submission;
    public $evaluation;

    public function mount(int $id)
    {
        $user    = Auth::user();
        $student = $user->student;

        $this->submissionId = $id;

        $this->submission = IASSubmission::with(['subject', 'student'])
            ->where('id', $id)
            ->where('student_id', $student?->id)
            ->firstOrFail();

        $this->evaluation = IASEvaluation::where('submission_id', $id)->first();
    }

    public function render()
    {
        return view('livewire.student.ias.evaluation-feedback', [
            'submission' => $this->submission,
            'evaluation' => $this->evaluation,
        ])->layout('layouts.student', ['title' => 'IAS Evaluation Feedback']);
    }
}
