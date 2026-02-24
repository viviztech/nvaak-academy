<?php

namespace App\Livewire\Admin\Exams;

use Livewire\Component;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\StudentAnswer;
use App\Models\ExamQuestion;
use App\Models\Subject;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class Analytics extends Component
{
    public int    $examId;
    public ?Exam  $exam = null;
    public string $activeTab = 'overview';

    public function mount(int $examId): void
    {
        $this->examId = $examId;
        $this->exam   = Exam::with(['batch', 'subject', 'sections'])->findOrFail($examId);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    private function getQuestionWiseData(): array
    {
        $examQuestions = ExamQuestion::where('exam_id', $this->examId)
            ->with('question.subject')
            ->orderBy('order')
            ->get();

        $data = [];
        foreach ($examQuestions as $eq) {
            $qId = $eq->question_id;
            $correct  = StudentAnswer::where('question_id', $qId)
                ->whereHas('studentExam', fn ($q) => $q->where('exam_id', $this->examId))
                ->where('is_correct', true)->count();
            $wrong    = StudentAnswer::where('question_id', $qId)
                ->whereHas('studentExam', fn ($q) => $q->where('exam_id', $this->examId))
                ->where('is_correct', false)->where('is_skipped', false)->count();
            $skipped  = StudentAnswer::where('question_id', $qId)
                ->whereHas('studentExam', fn ($q) => $q->where('exam_id', $this->examId))
                ->where('is_skipped', true)->count();
            $total    = $correct + $wrong + $skipped;
            $accuracy = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
            $avgTime  = StudentAnswer::where('question_id', $qId)
                ->whereHas('studentExam', fn ($q) => $q->where('exam_id', $this->examId))
                ->avg('time_spent_seconds');

            $data[] = [
                'position'    => $eq->order,
                'question_id' => $qId,
                'question'    => $eq->question,
                'correct'     => $correct,
                'wrong'       => $wrong,
                'skipped'     => $skipped,
                'total'       => $total,
                'accuracy'    => $accuracy,
                'avg_time'    => round($avgTime ?? 0),
            ];
        }

        return $data;
    }

    private function getSubjectWiseData(): array
    {
        $results = ExamResult::where('exam_id', $this->examId)
            ->whereNotNull('subject_wise_scores')
            ->get();

        $subjects = Subject::all()->keyBy('id');
        $subjectData = [];

        foreach ($results as $result) {
            foreach ($result->subject_wise_scores ?? [] as $subjectId => $scores) {
                if (! isset($subjectData[$subjectId])) {
                    $subjectData[$subjectId] = [
                        'subject'       => $subjects->get($subjectId),
                        'total_students'=> 0,
                        'total_correct' => 0,
                        'total_wrong'   => 0,
                        'sum_obtained'  => 0,
                        'sum_total'     => 0,
                    ];
                }
                $subjectData[$subjectId]['total_students']++;
                $subjectData[$subjectId]['total_correct'] += $scores['correct'] ?? 0;
                $subjectData[$subjectId]['total_wrong']   += $scores['wrong']   ?? 0;
                $subjectData[$subjectId]['sum_obtained']  += $scores['obtained']?? 0;
                $subjectData[$subjectId]['sum_total']     += $scores['total']   ?? 0;
            }
        }

        foreach ($subjectData as &$data) {
            $data['avg_score']  = $data['total_students'] > 0
                ? round($data['sum_obtained'] / $data['total_students'], 2) : 0;
            $data['avg_percent']= $data['sum_total'] > 0
                ? round(($data['sum_obtained'] / $data['sum_total']) * 100, 1) : 0;
        }

        return $subjectData;
    }

    private function getDifficultyDistribution(): array
    {
        return ExamQuestion::where('exam_id', $this->examId)
            ->join('questions', 'exam_questions.question_id', '=', 'questions.id')
            ->selectRaw('questions.difficulty, count(*) as total')
            ->groupBy('questions.difficulty')
            ->pluck('total', 'difficulty')
            ->toArray();
    }

    private function getScoreDistribution(): array
    {
        $results = ExamResult::where('exam_id', $this->examId)->pluck('percentage')->toArray();
        $ranges  = ['0-20' => 0, '21-40' => 0, '41-60' => 0, '61-80' => 0, '81-100' => 0];

        foreach ($results as $pct) {
            if ($pct <= 20)      $ranges['0-20']++;
            elseif ($pct <= 40)  $ranges['21-40']++;
            elseif ($pct <= 60)  $ranges['41-60']++;
            elseif ($pct <= 80)  $ranges['61-80']++;
            else                 $ranges['81-100']++;
        }

        return $ranges;
    }

    public function render()
    {
        $overviewStats = [
            'total_attempts' => ExamResult::where('exam_id', $this->examId)->count(),
            'pass'           => ExamResult::where('exam_id', $this->examId)->where('pass_fail', 'pass')->count(),
            'fail'           => ExamResult::where('exam_id', $this->examId)->where('pass_fail', 'fail')->count(),
            'avg_marks'      => round(ExamResult::where('exam_id', $this->examId)->avg('marks_obtained') ?? 0, 2),
            'avg_pct'        => round(ExamResult::where('exam_id', $this->examId)->avg('percentage') ?? 0, 1),
            'highest'        => ExamResult::where('exam_id', $this->examId)->max('marks_obtained') ?? 0,
            'lowest'         => ExamResult::where('exam_id', $this->examId)->min('marks_obtained') ?? 0,
            'avg_time'       => round(ExamResult::where('exam_id', $this->examId)->avg('time_taken_seconds') ?? 0),
        ];

        $questionWiseData    = $this->activeTab === 'questions' ? $this->getQuestionWiseData() : [];
        $subjectWiseData     = $this->activeTab === 'subjects'  ? $this->getSubjectWiseData()  : [];
        $difficultyDist      = $this->getDifficultyDistribution();
        $scoreDistribution   = $this->getScoreDistribution();

        return view('livewire.admin.exams.analytics', [
            'overviewStats'    => $overviewStats,
            'questionWiseData' => $questionWiseData,
            'subjectWiseData'  => $subjectWiseData,
            'difficultyDist'   => $difficultyDist,
            'scoreDistribution'=> $scoreDistribution,
        ])->layout('layouts.admin', ['title' => 'Analytics - ' . $this->exam->name]);
    }
}
