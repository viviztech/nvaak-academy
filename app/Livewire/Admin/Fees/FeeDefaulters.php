<?php

namespace App\Livewire\Admin\Fees;

use Livewire\Component;
use App\Models\Batch;
use App\Models\StudentFeeAssignment;
use App\Models\FeeInstallment;
use Carbon\Carbon;

class FeeDefaulters extends Component
{
    public string $batchFilter = '';
    public string $asOfDate    = '';

    public function mount(): void
    {
        $this->asOfDate = today()->toDateString();
    }

    public function getBatchesProperty()
    {
        return Batch::active()->orderBy('name')->get();
    }

    public function getDefaultersProperty()
    {
        $asOf = $this->asOfDate ?: today()->toDateString();

        return StudentFeeAssignment::with([
            'student.user',
            'batch',
            'feeStructure.installments',
            'payments',
        ])
        ->where('is_active', true)
        ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
        ->get()
        ->filter(function ($assignment) use ($asOf) {
            // Balance must be > 0
            if ($assignment->balance <= 0) {
                return false;
            }

            // At least one installment must be overdue
            $hasOverdueInstallment = $assignment->feeStructure->installments
                ->filter(function ($installment) use ($assignment, $asOf) {
                    $isDue = $installment->due_date->lte(Carbon::parse($asOf));
                    $hasPaid = $installment->payments()
                        ->where('fee_assignment_id', $assignment->id)
                        ->where('status', 'completed')
                        ->exists();

                    return $isDue && ! $hasPaid;
                })
                ->isNotEmpty();

            // If no installments exist but has balance, still show
            if ($assignment->feeStructure->installments->isEmpty() && $assignment->balance > 0) {
                return true;
            }

            return $hasOverdueInstallment;
        })
        ->values();
    }

    public function getOverdueDays(StudentFeeAssignment $assignment): int
    {
        $asOf     = $this->asOfDate ?: today()->toDateString();
        $earliest = $assignment->feeStructure->installments
            ->filter(function ($installment) use ($assignment) {
                return ! $installment->payments()
                    ->where('fee_assignment_id', $assignment->id)
                    ->where('status', 'completed')
                    ->exists();
            })
            ->sortBy('due_date')
            ->first();

        if (! $earliest) {
            return 0;
        }

        return (int) $earliest->due_date->diffInDays(Carbon::parse($asOf));
    }

    public function render()
    {
        return view('livewire.admin.fees.fee-defaulters', [
            'batches'   => $this->batches,
            'defaulters' => $this->defaulters,
        ])->layout('layouts.admin', ['title' => 'Fee Defaulters']);
    }
}
