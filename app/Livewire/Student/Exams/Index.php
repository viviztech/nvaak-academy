<?php

namespace App\Livewire\Student\Exams;

use Livewire\Component;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public string $activeTab = 'upcoming';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    private function getStudent(): ?Student
    {
        return Student::where('user_id', Auth::id())->first();
    }

    public function render()
    {
        $student = $this->getStudent();

        if (! $student) {
            return view('livewire.student.exams.index', [
                'upcomingExams'  => collect(),
                'liveExams'      => collect(),
                'completedExams' => collect(),
            ])->layout('layouts.student', ['title' => 'My Exams']);
        }

        $batchIds = $student->batches()->pluck('batches.id');

        // Upcoming exams: published, not yet started
        $upcomingExams = Exam::query()
            ->with(['batch', 'subject'])
            ->where('status', 'published')
            ->where('start_time', '>', now())
            ->where(fn ($q) =>
                $q->whereIn('batch_id', $batchIds)->orWhereNull('batch_id')
            )
            ->orderBy('start_time')
            ->get()
            ->map(function ($exam) use ($student) {
                $exam->student_exam = StudentExam::where('exam_id', $exam->id)
                    ->where('student_id', $student->id)->first();
                return $exam;
            });

        // Live exams: status=live, within time window
        $liveExams = Exam::query()
            ->with(['batch', 'subject'])
            ->where('status', 'live')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where(fn ($q) =>
                $q->whereIn('batch_id', $batchIds)->orWhereNull('batch_id')
            )
            ->orderBy('start_time')
            ->get()
            ->map(function ($exam) use ($student) {
                $exam->student_exam = StudentExam::where('exam_id', $exam->id)
                    ->where('student_id', $student->id)->first();
                return $exam;
            });

        // Completed: student has submitted
        $completedExams = StudentExam::query()
            ->with(['exam.batch', 'exam.subject', 'result'])
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->orderByDesc('submitted_at')
            ->get();

        return view('livewire.student.exams.index', [
            'upcomingExams'  => $upcomingExams,
            'liveExams'      => $liveExams,
            'completedExams' => $completedExams,
        ])->layout('layouts.student', ['title' => 'My Exams']);
    }
}
