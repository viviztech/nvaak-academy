<?php

use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/admissions/apply', \App\Livewire\Public\Admission\AdmissionForm::class)->name('admission.apply');
Route::get('/admissions/track', function () { return view('welcome'); })->name('admission.track');

// ─── Auth Routes ──────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:super-admin|admin|counsellor'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

    // Enquiries
    Route::get('/enquiries', \App\Livewire\Admin\Enquiries\Index::class)->name('enquiries.index');
    Route::get('/enquiries/create', \App\Livewire\Admin\Enquiries\Create::class)->name('enquiries.create');
    Route::get('/enquiries/{id}', \App\Livewire\Admin\Enquiries\Detail::class)->name('enquiries.detail');

    // Admissions
    Route::middleware('role:super-admin|admin')->group(function () {
        Route::get('/admissions', \App\Livewire\Admin\Admissions\Index::class)->name('admissions.index');
        Route::get('/admissions/{id}', \App\Livewire\Admin\Admissions\Detail::class)->name('admissions.detail');

        // Batches
        Route::get('/batches', \App\Livewire\Admin\Batches\Index::class)->name('batches.index');
        Route::get('/batches/{batch}', \App\Livewire\Admin\Batches\Detail::class)->name('batches.detail');
        Route::get('/batches/{batch}/faculty', \App\Livewire\Admin\Batches\FacultyAssignment::class)->name('batches.faculty');

        // Students
        Route::get('/students', \App\Livewire\Admin\Students\Index::class)->name('students.index');

        // Faculty
        Route::get('/faculty', \App\Livewire\Admin\Faculty\Index::class)->name('faculty.index');

        // Subjects & Chapters
        Route::get('/subjects', \App\Livewire\Admin\Subjects\Index::class)->name('subjects.index');

        // Syllabus
        Route::get('/syllabus', \App\Livewire\Admin\Syllabus\Index::class)->name('syllabus.index');
        Route::get('/syllabus/chapters', \App\Livewire\Admin\Subjects\Index::class)->name('syllabus.chapters');
        Route::get('/syllabus/coverage', \App\Livewire\Admin\Syllabus\Index::class)->name('syllabus.coverage');

        // Question Bank
        Route::get('/questions', \App\Livewire\Admin\Questions\Index::class)->name('questions.index');
        Route::get('/questions/create', \App\Livewire\Admin\Questions\QuestionForm::class)->name('questions.create');
        Route::get('/questions/{questionId}/edit', \App\Livewire\Admin\Questions\QuestionForm::class)->name('questions.edit');

        // Exams
        Route::get('/exams', \App\Livewire\Admin\Exams\Index::class)->name('exams.index');
        Route::get('/exams/create', \App\Livewire\Admin\Exams\ExamBuilder::class)->name('exams.create');
        Route::get('/exams/{examId}/edit', \App\Livewire\Admin\Exams\ExamBuilder::class)->name('exams.edit');
        Route::get('/exams/{examId}/rank-list', \App\Livewire\Admin\Exams\RankList::class)->name('exams.rank-list');
        Route::get('/exams/{examId}/analytics', \App\Livewire\Admin\Exams\Analytics::class)->name('exams.analytics');

        // Fees
        Route::get('/fees/structures', \App\Livewire\Admin\Fees\FeeStructures::class)->name('fees.structures');
        Route::get('/fees/payments', \App\Livewire\Admin\Fees\PaymentIndex::class)->name('fees.payments');
        Route::get('/fees/collect', \App\Livewire\Admin\Fees\CollectPayment::class)->name('fees.collect');
        Route::get('/fees/defaulters', \App\Livewire\Admin\Fees\FeeDefaulters::class)->name('fees.defaulters');

        // Attendance
        Route::get('/attendance/mark', \App\Livewire\Admin\Attendance\MarkAttendance::class)->name('attendance.mark');
        Route::get('/attendance/report', \App\Livewire\Admin\Attendance\AttendanceReport::class)->name('attendance.report');
        Route::get('/attendance/leaves', \App\Livewire\Admin\Attendance\LeaveManagement::class)->name('attendance.leaves');

        // Study Materials
        Route::get('/materials', \App\Livewire\Admin\StudyMaterials\Index::class)->name('materials.index');

        // Analytics — named admin.analytics.dashboard (used in admin layout)
        Route::get('/analytics', \App\Livewire\Admin\Analytics\OverallDashboard::class)->name('analytics.dashboard');
        Route::get('/analytics/rank-predictor', \App\Livewire\Admin\Analytics\StudentRankPredictor::class)->name('analytics.rank-predictor');

        // IAS
        Route::get('/ias/submissions', \App\Livewire\Admin\IAS\AnswerSubmissions::class)->name('ias.submissions');
        Route::get('/ias/evaluate/{submissionId}', \App\Livewire\Admin\IAS\EvaluationPanel::class)->name('ias.evaluate');

        // Communication
        Route::get('/communication/announcements', \App\Livewire\Admin\Communication\Announcements::class)->name('communication.announcements');
        Route::get('/communication/sms', \App\Livewire\Admin\Communication\BulkSms::class)->name('communication.sms');
    });
});

// ─── Teacher Routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:faculty'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Teacher\Dashboard::class)->name('dashboard');
    Route::get('/attendance', \App\Livewire\Teacher\Attendance\MarkAttendance::class)->name('attendance');
    Route::get('/syllabus', \App\Livewire\Teacher\Syllabus\CoverageUpdate::class)->name('syllabus');
    Route::get('/exams', \App\Livewire\Teacher\Exams\MyExams::class)->name('exams');
    Route::get('/ias/evaluate', \App\Livewire\Teacher\IAS\AnswerEvaluator::class)->name('ias.evaluate');
});

// ─── Student Routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Student\Dashboard::class)->name('dashboard');
    Route::get('/exams', \App\Livewire\Student\Exams\Index::class)->name('exams.index');
    Route::get('/exams/{examId}/take', \App\Livewire\Student\Exams\TakeExam::class)->name('exams.take');
    Route::get('/exams/{studentExamId}/result', \App\Livewire\Student\Exams\ExamResult::class)->name('exams.result');
    Route::get('/attendance', \App\Livewire\Student\AttendanceCalendar::class)->name('attendance');
    Route::get('/materials', \App\Livewire\Student\StudyMaterials::class)->name('materials');
    Route::get('/analytics', \App\Livewire\Student\PerformanceAnalytics::class)->name('analytics');
    Route::get('/fees', \App\Livewire\Student\Fees\PayFees::class)->name('fees');
    Route::get('/fees/history', \App\Livewire\Student\Fees\PaymentHistory::class)->name('fees.history');
    Route::get('/ias', \App\Livewire\Student\IAS\AnswerUpload::class)->name('ias.upload');
    Route::get('/ias/{id}/feedback', \App\Livewire\Student\IAS\EvaluationFeedback::class)->name('ias.feedback');
});

// ─── Role-based Redirect after Login ──────────────────────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole(['super-admin', 'admin', 'counsellor'])) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('faculty')) {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->hasRole('student')) {
        return redirect()->route('student.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
