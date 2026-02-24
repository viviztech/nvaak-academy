<?php

namespace App\Services;

use App\Models\StudentExam;
use App\Models\StudentAnswer;
use App\Models\ExamResult;
use App\Models\Question;

class ExamGradingService
{
    public function gradeExam(StudentExam $studentExam): ExamResult
    {
        $exam = $studentExam->exam()->with(['questions.question', 'sections'])->first();
        $answers = StudentAnswer::where('student_exam_id', $studentExam->id)->get()->keyBy('question_id');

        $totalMarks    = 0;
        $marksObtained = 0;
        $correct       = 0;
        $wrong         = 0;
        $unattempted   = 0;
        $subjectScores = [];

        foreach ($exam->questions as $eq) {
            $question = $eq->question;
            $answer   = $answers->get($question->id);

            if (! $answer || $answer->is_skipped) {
                $unattempted++;
                $marksForQuestion = $eq->marks ?? $question->marks;
                $totalMarks      += $marksForQuestion;

                // Track unattempted in subject scores
                $sid = $question->subject_id;
                if (! isset($subjectScores[$sid])) {
                    $subjectScores[$sid] = ['obtained' => 0, 'total' => 0, 'correct' => 0, 'wrong' => 0, 'unattempted' => 0];
                }
                $subjectScores[$sid]['unattempted']++;
                $subjectScores[$sid]['total'] += $marksForQuestion;
                continue;
            }

            $isCorrect        = $this->checkAnswer($question, $answer->given_answer);
            $marksForQuestion = $eq->marks ?? $question->marks;
            $negativeMarks    = $eq->negative_marks ?? $question->negative_marks;

            if ($isCorrect) {
                $awarded = $marksForQuestion;
                $correct++;
            } else {
                $awarded = $exam->negative_marking_enabled ? -$negativeMarks : 0;
                $wrong++;
            }

            $answer->update(['is_correct' => $isCorrect, 'marks_awarded' => $awarded]);
            $marksObtained += $awarded;
            $totalMarks    += $marksForQuestion;

            // Track subject scores
            $sid = $question->subject_id;
            if (! isset($subjectScores[$sid])) {
                $subjectScores[$sid] = ['obtained' => 0, 'total' => 0, 'correct' => 0, 'wrong' => 0, 'unattempted' => 0];
            }
            $subjectScores[$sid]['obtained'] += $awarded;
            $subjectScores[$sid]['total']    += $marksForQuestion;
            if ($isCorrect) {
                $subjectScores[$sid]['correct']++;
            } else {
                $subjectScores[$sid]['wrong']++;
            }
        }

        $percentage = $totalMarks > 0 ? round(($marksObtained / $totalMarks) * 100, 2) : 0;

        return ExamResult::updateOrCreate(
            ['student_exam_id' => $studentExam->id],
            [
                'exam_id'            => $studentExam->exam_id,
                'student_id'         => $studentExam->student_id,
                'batch_id'           => $studentExam->batch_id,
                'total_marks'        => $totalMarks,
                'marks_obtained'     => $marksObtained,
                'correct_answers'    => $correct,
                'wrong_answers'      => $wrong,
                'unattempted'        => $unattempted,
                'percentage'         => $percentage,
                'pass_fail'          => $marksObtained >= $exam->passing_marks ? 'pass' : 'fail',
                'time_taken_seconds' => $studentExam->time_taken_seconds,
                'subject_wise_scores'=> $subjectScores,
                'is_published'       => $exam->show_results_immediately,
            ]
        );
    }

    private function checkAnswer(Question $question, mixed $givenAnswer): bool
    {
        $correct     = $question->correct_answer;
        $givenAnswer = (array) $givenAnswer;

        return match ($question->question_type) {
            'mcq_single', 'true_false', 'assertion_reason' =>
                strtoupper(trim($givenAnswer[0] ?? '')) === strtoupper(trim($correct[0] ?? '')),

            'mcq_multiple' =>
                ! empty($correct) &&
                count($givenAnswer) === count($correct) &&
                empty(array_diff(
                    array_map('strtoupper', $givenAnswer),
                    array_map('strtoupper', (array) $correct)
                )),

            'numerical' =>
                isset($question->answer_range_from, $question->answer_range_to) &&
                floatval($givenAnswer[0] ?? 0) >= (float) $question->answer_range_from &&
                floatval($givenAnswer[0] ?? 0) <= (float) $question->answer_range_to,

            'match_following' => (function () use ($correct, $givenAnswer): bool {
                if (count($givenAnswer) !== count($correct)) return false;
                foreach ($correct as $i => $val) {
                    if (($givenAnswer[$i] ?? null) !== $val) return false;
                }
                return true;
            })(),

            default => false,
        };
    }

    public function generateRankList(int $examId): void
    {
        // Overall ranks
        $results = ExamResult::where('exam_id', $examId)
            ->orderByDesc('marks_obtained')
            ->orderBy('time_taken_seconds') // tie-break by time
            ->get();

        $rank = 1;
        foreach ($results as $result) {
            $result->update(['rank_overall' => $rank]);
            $rank++;
        }

        // Batch-wise ranks
        $batches = $results->pluck('batch_id')->unique()->filter();
        foreach ($batches as $batchId) {
            $batchResults = $results->where('batch_id', $batchId)->values();
            foreach ($batchResults as $i => $r) {
                $r->update(['rank_in_batch' => $i + 1]);
            }
        }
    }
}
