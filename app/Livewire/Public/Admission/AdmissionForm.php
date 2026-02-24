<?php

namespace App\Livewire\Public\Admission;

use App\Models\Admission;
use App\Models\Institute;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.public')]
#[Title('Apply for Admission - NVAAK Academy')]
class AdmissionForm extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;
    public int $totalSteps  = 5;

    // ---- Step 1: Personal Information ----
    public string $first_name        = '';
    public string $middle_name       = '';
    public string $last_name         = '';
    public string $date_of_birth     = '';
    public string $gender            = '';
    public string $blood_group       = '';
    public string $aadhaar_number    = '';
    public string $email             = '';
    public string $phone             = '';
    public string $alternate_phone   = '';
    public string $current_address   = '';
    public string $permanent_address = '';
    public string $city              = '';
    public string $state             = '';
    public string $postal_code       = '';
    public string $nationality       = 'Indian';
    public string $religion          = '';
    public string $caste_category    = '';

    // ---- Step 2: Academic Background ----
    public string $course_applied         = 'neet';
    public string $previous_institution   = '';
    public string $board                  = '';
    public string $previous_percentage    = '';
    public string $year_of_passing        = '';
    public string $neet_previous_score    = '';
    public string $neet_previous_rank     = '';
    public string $current_school_college = '';

    // ---- Step 3: Guardian Information ----
    public string $father_name       = '';
    public string $father_occupation = '';
    public string $father_phone      = '';
    public string $father_email      = '';
    public string $father_income     = '';
    public string $mother_name       = '';
    public string $mother_occupation = '';
    public string $mother_phone      = '';
    public string $mother_email      = '';
    public string $guardian_name     = '';
    public string $guardian_relation = '';
    public string $guardian_phone    = '';

    // ---- Step 4: Documents ----
    public $photo             = null;
    public $aadhaar_document  = null;
    public $marksheet         = null;

    protected function stepRules(): array
    {
        return [
            1 => [
                'first_name'        => 'required|string|max:100',
                'middle_name'       => 'nullable|string|max:100',
                'last_name'         => 'required|string|max:100',
                'date_of_birth'     => 'required|date|before:today',
                'gender'            => 'required|in:male,female,other',
                'blood_group'       => 'nullable|string|max:10',
                'aadhaar_number'    => 'nullable|string|max:20',
                'email'             => 'nullable|email|max:255',
                'phone'             => 'required|string|max:20',
                'alternate_phone'   => 'nullable|string|max:20',
                'current_address'   => 'nullable|string|max:500',
                'permanent_address' => 'nullable|string|max:500',
                'city'              => 'nullable|string|max:100',
                'state'             => 'nullable|string|max:100',
                'postal_code'       => 'nullable|string|max:10',
                'nationality'       => 'nullable|string|max:50',
                'religion'          => 'nullable|string|max:50',
                'caste_category'    => 'nullable|string|max:50',
            ],
            2 => [
                'course_applied'         => 'required|in:neet,ias,both',
                'previous_institution'   => 'nullable|string|max:255',
                'board'                  => 'nullable|string|max:100',
                'previous_percentage'    => 'nullable|numeric|min:0|max:100',
                'year_of_passing'        => 'nullable|digits:4|integer|min:2000|max:' . (date('Y') + 1),
                'neet_previous_score'    => 'nullable|numeric|min:0|max:720',
                'neet_previous_rank'     => 'nullable|integer|min:1',
                'current_school_college' => 'nullable|string|max:255',
            ],
            3 => [
                'father_name'       => 'required|string|max:150',
                'father_occupation' => 'nullable|string|max:150',
                'father_phone'      => 'required|string|max:20',
                'father_email'      => 'nullable|email|max:255',
                'father_income'     => 'nullable|string|max:50',
                'mother_name'       => 'nullable|string|max:150',
                'mother_occupation' => 'nullable|string|max:150',
                'mother_phone'      => 'nullable|string|max:20',
                'mother_email'      => 'nullable|email|max:255',
                'guardian_name'     => 'nullable|string|max:150',
                'guardian_relation' => 'nullable|string|max:100',
                'guardian_phone'    => 'nullable|string|max:20',
            ],
            4 => [
                'photo'            => 'nullable|image|max:2048',
                'aadhaar_document' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
                'marksheet'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            ],
            5 => [],
        ];
    }

    public function nextStep(): void
    {
        $this->validate($this->stepRules()[$this->currentStep] ?? []);

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function submit(): void
    {
        // Final validation of all steps
        foreach ($this->stepRules() as $stepRules) {
            if (!empty($stepRules)) {
                $this->validate($stepRules);
            }
        }

        $applicationNumber = Admission::generateApplicationNumber();

        // Store uploaded files
        $documentPaths = [];

        if ($this->photo) {
            $path = $this->photo->store(
                'admissions/' . $applicationNumber . '/photo',
                'public'
            );
            $documentPaths['photo'] = $path;
        }

        if ($this->aadhaar_document) {
            $path = $this->aadhaar_document->store(
                'admissions/' . $applicationNumber . '/aadhaar',
                'public'
            );
            $documentPaths['aadhaar'] = $path;
        }

        if ($this->marksheet) {
            $path = $this->marksheet->store(
                'admissions/' . $applicationNumber . '/marksheet',
                'public'
            );
            $documentPaths['marksheet'] = $path;
        }

        $institute = Institute::first();

        $admission = Admission::create([
            'institute_id'         => $institute->id,
            'application_number'   => $applicationNumber,
            'status'               => 'submitted',
            'submitted_at'         => now(),

            // Personal
            'first_name'           => $this->first_name,
            'middle_name'          => $this->middle_name ?: null,
            'last_name'            => $this->last_name,
            'date_of_birth'        => $this->date_of_birth,
            'gender'               => $this->gender,
            'blood_group'          => $this->blood_group ?: null,
            'aadhaar_number'       => $this->aadhaar_number ?: null,
            'email'                => $this->email ?: null,
            'phone'                => $this->phone,
            'alternate_phone'      => $this->alternate_phone ?: null,
            'current_address'      => $this->current_address ?: null,
            'permanent_address'    => $this->permanent_address ?: null,
            'city'                 => $this->city ?: null,
            'state'                => $this->state ?: null,
            'postal_code'          => $this->postal_code ?: null,
            'nationality'          => $this->nationality ?: 'Indian',
            'religion'             => $this->religion ?: null,
            'caste_category'       => $this->caste_category ?: null,

            // Academic
            'course_applied'       => $this->course_applied,
            'previous_institution' => $this->previous_institution ?: null,
            'board'                => $this->board ?: null,
            'previous_percentage'  => $this->previous_percentage ?: null,
            'year_of_passing'      => $this->year_of_passing ?: null,
            'neet_previous_score'  => $this->neet_previous_score ?: null,
            'neet_previous_rank'   => $this->neet_previous_rank ?: null,
            'current_school_college' => $this->current_school_college ?: null,

            // Guardian
            'father_name'          => $this->father_name,
            'father_occupation'    => $this->father_occupation ?: null,
            'father_phone'         => $this->father_phone,
            'father_email'         => $this->father_email ?: null,
            'father_income'        => $this->father_income ?: null,
            'mother_name'          => $this->mother_name ?: null,
            'mother_occupation'    => $this->mother_occupation ?: null,
            'mother_phone'         => $this->mother_phone ?: null,
            'mother_email'         => $this->mother_email ?: null,
            'guardian_name'        => $this->guardian_name ?: null,
            'guardian_relation'    => $this->guardian_relation ?: null,
            'guardian_phone'       => $this->guardian_phone ?: null,

            // Documents
            'documents'            => !empty($documentPaths) ? $documentPaths : null,
        ]);

        session()->put('application_number', $applicationNumber);
        session()->flash('success', 'Application submitted successfully! Your application number is ' . $applicationNumber);

        $this->redirect(route('admission.track', ['application_number' => $applicationNumber]));
    }

    public function render()
    {
        return view('livewire.public.admission.admission-form');
    }
}
