<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Leave;
use App\Models\Batch;
use Illuminate\Support\Facades\Auth;

class LeaveManagement extends Component
{
    use WithPagination;

    public string $statusFilter = 'pending';
    public string $batchFilter  = '';
    public int    $perPage      = 20;

    public function getLeavesProperty()
    {
        return Leave::with(['student.user', 'batch'])
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->orderByDesc('applied_at')
            ->paginate($this->perPage);
    }

    public function approve(int $id): void
    {
        Leave::findOrFail($id)->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
        ]);
        session()->flash('success', 'Leave approved successfully.');
    }

    public function reject(int $id): void
    {
        Leave::findOrFail($id)->update([
            'status'      => 'rejected',
            'approved_by' => Auth::id(),
        ]);
        session()->flash('success', 'Leave rejected.');
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.attendance.leave-management', [
            'leaves'  => $this->leaves,
            'batches' => Batch::active()->orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Leave Management']);
    }
}
