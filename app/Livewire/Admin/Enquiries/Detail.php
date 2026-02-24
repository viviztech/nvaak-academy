<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\EnquiryFollowUp;
use App\Models\Admission;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

#[Layout('layouts.admin')]
class Detail extends Component
{
    public Enquiry $enquiry;

    // Follow-up modal state
    public bool $showFollowUpModal = false;

    // Follow-up form properties
    public string $follow_up_type = 'call';
    public string $notes = '';
    public string $outcome = 'no_response';
    public string $next_follow_up_at = '';
    public bool $is_converted = false;

    // Assign / status
    public string $newStatus = '';
    public string $newAssignedTo = '';

    // Admit modal
    public bool $showAdmitModal = false;

    protected function rules(): array
    {
        return [
            'follow_up_type'    => 'required|in:call,email,whatsapp,in_person,sms',
            'notes'             => 'required|string|max:2000',
            'outcome'           => 'required|in:interested,not_interested,callback,no_response,converted',
            'next_follow_up_at' => 'nullable|date|after:now',
            'is_converted'      => 'boolean',
        ];
    }

    public function mount(int $id): void
    {
        $this->enquiry = Enquiry::with(['followUps.createdBy', 'assignedTo'])
            ->findOrFail($id);

        $this->newStatus     = $this->enquiry->status;
        $this->newAssignedTo = (string) ($this->enquiry->assigned_to ?? '');
    }

    #[Computed]
    public function staffUsers()
    {
        return User::orderBy('name')->get(['id', 'name']);
    }

    public function addFollowUp(): void
    {
        $this->validate();

        $followUp = EnquiryFollowUp::create([
            'enquiry_id'        => $this->enquiry->id,
            'created_by'        => auth()->id(),
            'follow_up_type'    => $this->follow_up_type,
            'notes'             => $this->notes,
            'outcome'           => $this->outcome,
            'next_follow_up_at' => $this->next_follow_up_at ?: null,
            'followed_up_at'    => now(),
        ]);

        if ($this->is_converted || $this->outcome === 'converted') {
            $this->enquiry->update(['status' => 'converted']);
        } elseif ($this->outcome === 'interested') {
            $this->enquiry->update(['status' => 'interested']);
        } elseif ($this->outcome === 'not_interested') {
            $this->enquiry->update(['status' => 'not_interested']);
        } else {
            $this->enquiry->update(['status' => 'contacted']);
        }

        $this->enquiry->refresh()->load(['followUps.createdBy', 'assignedTo']);
        $this->newStatus = $this->enquiry->status;

        $this->resetFollowUpForm();
        $this->showFollowUpModal = false;

        session()->flash('success', 'Follow-up recorded successfully.');
    }

    public function updateStatus(string $status): void
    {
        $allowed = ['new', 'contacted', 'interested', 'not_interested', 'converted', 'lost'];
        if (!in_array($status, $allowed)) {
            return;
        }

        $this->enquiry->update(['status' => $status]);
        $this->enquiry->refresh();
        $this->newStatus = $status;

        session()->flash('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $status)) . '.');
    }

    public function assignTo(string $userId): void
    {
        $this->enquiry->update(['assigned_to' => $userId ?: null]);
        $this->enquiry->refresh()->load(['followUps.createdBy', 'assignedTo']);
        $this->newAssignedTo = $userId;

        session()->flash('success', 'Enquiry assigned successfully.');
    }

    public function convertToAdmission(): void
    {
        if ($this->enquiry->status === 'converted') {
            session()->flash('error', 'This enquiry is already converted.');
            return;
        }

        $admission = Admission::create([
            'enquiry_id'         => $this->enquiry->id,
            'first_name'         => $this->enquiry->first_name,
            'last_name'          => $this->enquiry->last_name,
            'email'              => $this->enquiry->email,
            'phone'              => $this->enquiry->phone,
            'course_applied'     => $this->enquiry->course_interest ?? 'neet',
            'status'             => 'draft',
            'application_number' => Admission::generateApplicationNumber(),
        ]);

        $this->enquiry->update(['status' => 'converted']);
        $this->enquiry->refresh();
        $this->newStatus = 'converted';

        session()->flash('success', 'Converted to admission. Application #' . $admission->application_number);
        $this->redirect(route('admin.admissions.show', $admission->id));
    }

    private function resetFollowUpForm(): void
    {
        $this->follow_up_type    = 'call';
        $this->notes             = '';
        $this->outcome           = 'no_response';
        $this->next_follow_up_at = '';
        $this->is_converted      = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.admin.enquiries.detail')
            ->title($this->enquiry->first_name . ' ' . $this->enquiry->last_name . ' - Enquiry');
    }
}
