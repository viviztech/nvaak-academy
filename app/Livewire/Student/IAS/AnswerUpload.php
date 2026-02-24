<?php

namespace App\Livewire\Student\IAS;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\IASSubmission;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class AnswerUpload extends Component
{
    use WithFileUploads;

    public $subject_id       = '';
    public $question_text    = '';
    public $answer_file      = null;
    public $subjects         = [];
    public $mySubmissions    = [];
    public $uploadSuccess    = false;

    protected $rules = [
        'subject_id'    => 'required|exists:subjects,id',
        'question_text' => 'required|string|min:10|max:1000',
        'answer_file'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
    ];

    protected $messages = [
        'answer_file.mimes' => 'Only PDF and image files (JPG, PNG) are allowed.',
        'answer_file.max'   => 'File size must not exceed 10 MB.',
    ];

    public function mount()
    {
        $user    = Auth::user();
        $student = $user->student;

        if ($student) {
            $batchIds = $student->batches()->pluck('batches.id');
            $this->subjects = Subject::whereHas('batches', function ($q) use ($batchIds) {
                $q->whereIn('batches.id', $batchIds);
            })->orderBy('name')->get()->toArray();

            $this->loadMySubmissions($student->id);
        }
    }

    protected function loadMySubmissions(int $studentId)
    {
        $this->mySubmissions = IASSubmission::with(['subject', 'evaluation'])
            ->where('student_id', $studentId)
            ->orderByDesc('submitted_at')
            ->take(10)
            ->get()
            ->toArray();
    }

    public function upload()
    {
        $this->validate();

        $user    = Auth::user();
        $student = $user->student;

        if (!$student) {
            $this->addError('answer_file', 'Student profile not found.');
            return;
        }

        // Store the file
        $path = $this->answer_file->store('ias-answers/' . $student->id, 'private');

        IASSubmission::create([
            'student_id'    => $student->id,
            'subject_id'    => $this->subject_id,
            'question_text' => $this->question_text,
            'file_path'     => $path,
            'file_name'     => $this->answer_file->getClientOriginalName(),
            'status'        => 'pending',
            'submitted_at'  => now(),
        ]);

        $this->reset(['subject_id', 'question_text', 'answer_file']);
        $this->uploadSuccess = true;
        $this->loadMySubmissions($student->id);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Answer sheet submitted successfully.']);
    }

    public function render()
    {
        return view('livewire.student.ias.answer-upload')
            ->layout('layouts.student', ['title' => 'IAS Answer Upload']);
    }
}
