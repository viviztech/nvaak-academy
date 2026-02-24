<?php

namespace App\Livewire\Admin\Admissions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Admission;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public int $perPage = 15;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function approve(int $id): void
    {
        $admission = Admission::findOrFail($id);
        $admission->update(['status' => 'approved']);
        session()->flash('success', 'Admission approved.');
    }

    public function reject(int $id): void
    {
        $admission = Admission::findOrFail($id);
        $admission->update(['status' => 'rejected']);
        session()->flash('success', 'Admission rejected.');
    }

    public function render()
    {
        $admissions = Admission::with(['student', 'batch'])
            ->when($this->search, fn($q) => $q->where(function ($sq) {
                $sq->where('application_number', 'like', "%{$this->search}%")
                   ->orWhere('applicant_name', 'like', "%{$this->search}%")
                   ->orWhere('applicant_phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.admissions.index', [
            'admissions'  => $admissions,
            'totalCount'  => Admission::count(),
            'pendingCount'  => Admission::where('status', 'pending')->count(),
            'approvedCount' => Admission::where('status', 'approved')->count(),
        ])->layout('layouts.admin', ['title' => 'Admissions']);
    }
}
