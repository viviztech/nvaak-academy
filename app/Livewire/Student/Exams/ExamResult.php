<?php

namespace App\Livewire\Student\Exams;

use Livewire\Component;
use App\Models\StudentExam;
use App\Models\ExamResult as ExamResultModel;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Auth;

class ExamResult extends Component
{
    public int $studentExamId;
    public ?StudentExam $studentExam     = null;
    public ?ExamResultModel $result      = null;
    public bool $showSolutions           = false;
    public string $activeTab             = 'scorecard';
    public string $filterSection         = '';

    public function mount(int $studentExamId): void
    {
        $this->studentExamId = $studentExamId;
        $this->studentExam   = StudentExam::with(['exam', 'exam.sections', 'result'])
            ->findOrFail($studentExamId);

        // Security: only the student who took the exam can view their result
        $student = \App\Models\Student::where('user_id', Auth::id())->first();
        if (! $student || $this->studentExam->student_id !== $student->id) {
            abort(403, 'Access denied.');
        }

        $this->result = $this->studentExam->result;
        $this->showSolutions = $this->studentExam->exam->show_correct_answers ?? false;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $exam     = $this->studentExam->exam;
        $result   = $this->result;
        $subjects = collect();

        if ($result && $result->subject_wise_scores) {
            $subjects = Subject::whereIn('id', array_keys($result->subject_wise_scores))->get()->keyBy('id');
        }

        // Batch rank context
        $batchRank    = $result?->rank_in_batch;
        $overallRank  = $result?->rank_overall;
        $totalInBatch = ExamResultModel::where('exam_id', $exam->id)
            ->when($result?->batch_id, fn ($q) => $q->where('batch_id', $result->batch_id))
            ->count();
        $percentile   = $totalInBatch > 0 && $batchRank
            ? round((($totalInBatch - $batchRank) / $totalInBatch) * 100, 1)
            : null;

        // Solutions (question by question)
        $solutions = [];
        if ($this->activeTab === 'solutions') {
            $examQuestions = ExamQuestion::where('exam_id', $exam->id)
                ->with('question')
                ->orderBy('order')
                ->when($this->filterSection, fn ($q) => $q->where('exam_section_id', $this->filterSection))
                ->get();

            $savedAnswers = StudentAnswer::where('student_exam_id', $this->studentExamId)
                ->get()->keyBy('question_id');

            foreach ($examQuestions as $eq) {
                $q = $eq->question;
                $ans = $savedAnswers->get($q->id);
                $solutions[] = [
                    'question'     => $q,
                    'given_answer' => $ans?->given_answer ?? [],
                    'is_correct'   => $ans?->is_correct ?? false,
                    'is_skipped'   => $ans?->is_skipped ?? true,
                    'marks_awarded'=> $ans?->marks_awarded ?? 0,
                    'time_spent'   => $ans?->time_spent_seconds ?? 0,
                ];
            }
        }

        // Strength/weakness: based on subject-wise accuracy
        $strengths   = [];
        $weaknesses  = [];
        if ($result && $result->subject_wise_scores) {
            foreach ($result->subject_wise_scores as $sid => $scores) {
                $attempted = ($scores['correct'] ?? 0) + ($scores['wrong'] ?? 0);
                $accuracy  = $attempted > 0
                    ? round(($scores['correct'] / $attempted) * 100, 1) : 0;
                $subName   = $subjects->get($sid)?->name ?? "Subject $sid";
                if ($accuracy >= 70) {
                    $strengths[]  = ['subject' => $subName, 'accuracy' => $accuracy];
                } elseif ($accuracy < 50) {
                    $weaknesses[] = ['subject' => $subName, 'accuracy' => $accuracy];
                }
            }
        }

        return view('livewire.student.exams.exam-result', [
            'exam'         => $exam,
            'result'       => $result,
            'subjects'     => $subjects,
            'batchRank'    => $batchRank,
            'overallRank'  => $overallRank,
            'totalInBatch' => $totalInBatch,
            'percentile'   => $percentile,
            'solutions'    => $solutions,
            'strengths'    => $strengths,
            'weaknesses'   => $weaknesses,
        ])->layout('layouts.student', ['title' => 'Exam Result']);
    }
}
