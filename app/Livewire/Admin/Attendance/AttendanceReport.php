<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Batch;
use App\Services\AttendanceReportService;
use Carbon\Carbon;

class AttendanceReport extends Component
{
    use WithPagination;

    public string $batchId    = '';
    public string $fromDate   = '';
    public string $toDate     = '';
    public bool   $generating = false;

    public function mount(): void
    {
        $this->fromDate = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate   = Carbon::now()->toDateString();
    }

    public function getReportProperty(): array
    {
        if (! $this->batchId) {
            return [];
        }

        $service = app(AttendanceReportService::class);

        return $service->getBatchAttendanceReport(
            (int) $this->batchId,
            $this->fromDate,
            $this->toDate
        );
    }

    public function generateReport(): void
    {
        $this->generating = true;
    }

    public function render()
    {
        return view('livewire.admin.attendance.attendance-report', [
            'batches' => Batch::active()->orderBy('name')->get(),
            'report'  => $this->report,
        ])->layout('layouts.admin', ['title' => 'Attendance Report']);
    }
}
