<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

#[Layout('layouts.admin')]
#[Title('Add Enquiry')]
class Create extends Component
{
    // Source & Lead Info
    public string $source = 'walk_in';

    // Personal Info
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $alternate_phone = '';
    public string $city = '';
    public string $state = '';
    public string $date_of_birth = '';
    public string $gender = '';

    // Academic Interest
    public string $course_interest = 'neet';
    public string $batch_interest = '';
    public string $academic_background = '';
    public string $previous_marks = '';
    public string $current_school_college = '';

    // Additional Info
    public string $query_notes = '';
    public string $referral_name = '';
    public string $assigned_to = '';
    public string $priority = 'medium';

    protected function rules(): array
    {
        return [
            'source'               => 'required|in:walk_in,phone,website,social,referral',
            'first_name'           => 'required|string|max:100',
            'last_name'            => 'required|string|max:100',
            'email'                => 'nullable|email|max:255',
            'phone'                => 'required|string|max:20',
            'alternate_phone'      => 'nullable|string|max:20',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'date_of_birth'        => 'nullable|date|before:today',
            'gender'               => 'nullable|in:male,female,other',
            'course_interest'      => 'required|in:neet,ias,both',
            'batch_interest'       => 'nullable|string|max:255',
            'academic_background'  => 'nullable|string|max:255',
            'previous_marks'       => 'nullable|string|max:50',
            'current_school_college' => 'nullable|string|max:255',
            'query_notes'          => 'nullable|string|max:2000',
            'referral_name'        => 'nullable|string|max:255',
            'assigned_to'          => 'nullable|exists:users,id',
            'priority'             => 'required|in:high,medium,low',
        ];
    }

    #[Computed]
    public function staffUsers()
    {
        return User::orderBy('name')->get(['id', 'name']);
    }

    public function save(): void
    {
        $validated = $this->validate();

        $enquiry = Enquiry::create([
            'source'               => $validated['source'],
            'first_name'           => $validated['first_name'],
            'last_name'            => $validated['last_name'],
            'email'                => $validated['email'] ?: null,
            'phone'                => $validated['phone'],
            'alternate_phone'      => $validated['alternate_phone'] ?: null,
            'city'                 => $validated['city'] ?: null,
            'state'                => $validated['state'] ?: null,
            'date_of_birth'        => $validated['date_of_birth'] ?: null,
            'gender'               => $validated['gender'] ?: null,
            'course_interest'      => $validated['course_interest'],
            'batch_interest'       => $validated['batch_interest'] ?: null,
            'academic_background'  => $validated['academic_background'] ?: null,
            'previous_marks'       => $validated['previous_marks'] ?: null,
            'current_school_college' => $validated['current_school_college'] ?: null,
            'query_notes'          => $validated['query_notes'] ?: null,
            'referral_name'        => $validated['referral_name'] ?: null,
            'assigned_to'          => $validated['assigned_to'] ?: null,
            'priority'             => $validated['priority'],
            'status'               => 'new',
        ]);

        session()->flash('success', 'Enquiry added successfully.');
        $this->redirect(route('admin.enquiries.show', $enquiry->id));
    }

    public function render()
    {
        return view('livewire.admin.enquiries.create');
    }
}
