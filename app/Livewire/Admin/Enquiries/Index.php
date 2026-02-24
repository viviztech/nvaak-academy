<?php

namespace App\Livewire\Admin\Enquiries;

use App\Models\Enquiry;
use App\Models\Admission;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Enquiries')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sourceFilter = '';
    public int $perPage = 15;
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    protected $queryString = [
        'search'       => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sourceFilter' => ['except' => ''],
        'perPage'      => ['except' => 15],
        'sortBy'       => ['except' => 'created_at'],
        'sortDir'      => ['except' => 'desc'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSourceFilter(): void
    {
        $this->resetPage();
    }

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'asc';
        }
        $this->resetPage();
    }

    public function deleteEnquiry(int $id): void
    {
        $enquiry = Enquiry::findOrFail($id);
        $enquiry->delete();
        session()->flash('success', 'Enquiry deleted successfully.');
    }

    public function convertToAdmission(int $id): void
    {
        $enquiry = Enquiry::findOrFail($id);

        if ($enquiry->status === 'converted') {
            session()->flash('error', 'This enquiry has already been converted.');
            return;
        }

        $admission = Admission::create([
            'enquiry_id'      => $enquiry->id,
            'first_name'      => $enquiry->first_name,
            'last_name'       => $enquiry->last_name,
            'email'           => $enquiry->email,
            'phone'           => $enquiry->phone,
            'course_applied'  => $enquiry->course_interest ?? 'neet',
            'status'          => 'draft',
            'application_number' => Admission::generateApplicationNumber(),
        ]);

        $enquiry->update(['status' => 'converted']);

        session()->flash('success', 'Enquiry converted to admission successfully.');
        $this->redirect(route('admin.admissions.show', $admission->id));
    }

    #[Computed]
    public function enquiries()
    {
        return Enquiry::with('assignedTo')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->sourceFilter, fn($query) => $query->where('source', $this->sourceFilter))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);
    }

    #[Computed]
    public function totalCount(): int
    {
        return Enquiry::count();
    }

    #[Computed]
    public function newTodayCount(): int
    {
        return Enquiry::whereDate('created_at', today())->where('status', 'new')->count();
    }

    #[Computed]
    public function convertedCount(): int
    {
        return Enquiry::where('status', 'converted')->count();
    }

    #[Computed]
    public function followUpsDueCount(): int
    {
        return Enquiry::whereHas('followUps', function ($q) {
            $q->whereDate('next_follow_up_at', today())
              ->where('outcome', '!=', 'converted');
        })->count();
    }

    public function render()
    {
        return view('livewire.admin.enquiries.index');
    }
}
