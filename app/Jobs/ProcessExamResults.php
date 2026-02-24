<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\StudentExam;
use App\Services\ExamGradingService;
use Illuminate\Support\Facades\Log;

class ProcessExamResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public int $studentExamId) {}

    public function handle(ExamGradingService $gradingService): void
    {
        $studentExam = StudentExam::findOrFail($this->studentExamId);

        if ($studentExam->status !== 'submitted') {
            Log::warning("ProcessExamResults: StudentExam {$this->studentExamId} is not submitted.");
            return;
        }

        try {
            $result = $gradingService->gradeExam($studentExam);
            $gradingService->generateRankList($studentExam->exam_id);
            Log::info("ProcessExamResults: Graded studentExam {$this->studentExamId}, marks = {$result->marks_obtained}");
        } catch (\Throwable $e) {
            Log::error("ProcessExamResults failed for studentExam {$this->studentExamId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessExamResults job permanently failed for studentExam {$this->studentExamId}: " . $exception->getMessage());
    }
}
