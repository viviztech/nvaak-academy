<?php

namespace App\Livewire\Admin\Admissions;

use App\Models\Admission;
use App\Models\Batch;
use Livewire\Component;

class Detail extends Component
{
    public Admission $admission;

    public string $adminRemarks   = '';
    public string $rejectionReason = '';
    public string $selectedBatchId = '';

    public bool $showRejectModal = false;

    public function mount(int $id): void
    {
        $this->admission = Admission::with(['batch', 'reviewedBy', 'institute'])->findOrFail($id);
        $this->adminRemarks    = $this->admission->admin_remarks ?? '';
        $this->selectedBatchId = $this->admission->batch_id ?? '';
    }

    public function approve(): void
    {
        $this->admission->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_remarks' => $this->adminRemarks ?: null,
            'batch_id'    => $this->selectedBatchId ?: null,
        ]);

        $this->admission->refresh();
        session()->flash('success', 'Application approved successfully.');
    }

    public function reject(): void
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10',
        ]);

        $this->admission->update([
            'status'           => 'rejected',
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $this->rejectionReason,
            'admin_remarks'    => $this->adminRemarks ?: null,
        ]);

        $this->admission->refresh();
        $this->showRejectModal = false;
        session()->flash('success', 'Application rejected.');
    }

    public function saveRemarks(): void
    {
        $this->admission->update(['admin_remarks' => $this->adminRemarks ?: null]);
        session()->flash('success', 'Remarks saved.');
    }

    public function render()
    {
        return view('livewire.admin.admissions.detail', [
            'batches' => Batch::orderBy('name')->get(['id', 'name']),
        ])->layout('layouts.admin', ['title' => 'Admission Detail']);
    }
}
