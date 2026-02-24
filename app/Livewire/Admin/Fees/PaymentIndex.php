<?php

namespace App\Livewire\Admin\Fees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FeePayment;
use Carbon\Carbon;

class PaymentIndex extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $dateFrom   = '';
    public string $dateTo     = '';
    public string $modeFilter = '';
    public int    $perPage    = 20;

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo   = Carbon::now()->toDateString();
    }

    public function getPaymentsProperty()
    {
        return FeePayment::with(['student.user', 'feeAssignment.batch'])
            ->when($this->search, function ($q) {
                $q->whereHas('student.user', fn ($u) => $u->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhere('receipt_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->dateFrom, fn ($q) => $q->whereDate('payment_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('payment_date', '<=', $this->dateTo))
            ->when($this->modeFilter, fn ($q) => $q->where('payment_mode', $this->modeFilter))
            ->where('status', 'completed')
            ->orderByDesc('payment_date')
            ->paginate($this->perPage);
    }

    public function getTodayTotalProperty(): float
    {
        return (float) FeePayment::where('status', 'completed')
            ->whereDate('payment_date', today())
            ->sum('amount_paid');
    }

    public function getMonthTotalProperty(): float
    {
        return (float) FeePayment::where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.fees.payment-index', [
            'payments'   => $this->payments,
            'todayTotal' => $this->todayTotal,
            'monthTotal' => $this->monthTotal,
        ])->layout('layouts.admin', ['title' => 'Fee Payments']);
    }
}
