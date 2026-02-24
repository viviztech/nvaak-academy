<?php

namespace App\Livewire\Admin\Fees;

use Livewire\Component;
use App\Models\StudentFeeAssignment;
use App\Models\FeePayment;
use App\Models\FeeInstallment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CollectPayment extends Component
{
    // Step 1: student search
    public string $searchQuery  = '';
    public ?int   $assignmentId = null;

    // Step 2: loaded assignment
    public $assignment = null;
    public $student    = null;

    // Payment form
    public string $amount                = '';
    public string $payment_mode          = 'cash';
    public string $transaction_reference = '';
    public string $bank_name             = '';
    public string $cheque_number         = '';
    public string $cheque_date           = '';
    public string $remarks               = '';
    public string $selectedInstallmentId = '';

    public function mount(?int $assignmentId = null): void
    {
        if ($assignmentId) {
            $this->loadAssignment($assignmentId);
        }
    }

    public function getStudentResultsProperty()
    {
        if (strlen($this->searchQuery) < 2) {
            return collect();
        }

        return Student::with(['user', 'feeAssignments.feeStructure', 'feeAssignments.batch'])
            ->where(function ($q) {
                $q->whereHas('user', fn($uq) =>
                    $uq->where('name', 'like', "%{$this->searchQuery}%")
                       ->orWhere('phone', 'like', "%{$this->searchQuery}%")
                )->orWhere('student_code', 'like', "%{$this->searchQuery}%");
            })
            ->limit(8)
            ->get();
    }

    public function selectAssignment(int $assignmentId): void
    {
        $this->loadAssignment($assignmentId);
        $this->searchQuery = '';
    }

    public function clearAssignment(): void
    {
        $this->assignment   = null;
        $this->assignmentId = null;
        $this->student      = null;
        $this->resetPaymentForm();
    }

    private function loadAssignment(int $assignmentId): void
    {
        $this->assignment = StudentFeeAssignment::with([
            'student.user',
            'batch',
            'feeStructure.installments',
            'payments',
        ])->findOrFail($assignmentId);

        $this->assignmentId = $assignmentId;
        $this->student      = $this->assignment->student;
    }

    public function getUnpaidInstallmentsProperty()
    {
        if (! $this->assignment) {
            return collect();
        }

        return $this->assignment->feeStructure->installments
            ->filter(function ($installment) {
                return ! FeePayment::where('installment_id', $installment->id)
                    ->where('fee_assignment_id', $this->assignmentId)
                    ->where('status', 'completed')
                    ->exists();
            });
    }

    public function updatedSelectedInstallmentId(string $value): void
    {
        if ($value) {
            $installment  = FeeInstallment::find($value);
            $this->amount = $installment ? (string) $installment->amount : '';
        }
    }

    public function pay(): void
    {
        $this->validate([
            'amount'        => 'required|numeric|min:1',
            'payment_mode'  => 'required|in:cash,online,dd,cheque,upi',
            'cheque_number' => 'required_if:payment_mode,cheque',
            'cheque_date'   => 'required_if:payment_mode,cheque',
            'bank_name'     => 'required_if:payment_mode,dd',
        ]);

        FeePayment::create([
            'institute_id'          => $this->student->institute_id,
            'student_id'            => $this->student->id,
            'fee_assignment_id'     => $this->assignmentId,
            'installment_id'        => $this->selectedInstallmentId ?: null,
            'receipt_number'        => FeePayment::generateReceiptNumber(),
            'payment_date'          => now()->toDateString(),
            'amount_paid'           => $this->amount,
            'payment_mode'          => $this->payment_mode,
            'transaction_reference' => $this->transaction_reference ?: null,
            'bank_name'             => $this->bank_name ?: null,
            'cheque_number'         => $this->cheque_number ?: null,
            'cheque_date'           => $this->cheque_date ?: null,
            'remarks'               => $this->remarks ?: null,
            'collected_by'          => Auth::id(),
            'status'                => 'completed',
        ]);

        session()->flash('success', 'Payment of ₹' . number_format($this->amount, 2) . ' recorded successfully.');
        $this->resetPaymentForm();
        $this->loadAssignment($this->assignmentId); // refresh balance
    }

    private function resetPaymentForm(): void
    {
        $this->amount = $this->transaction_reference = $this->bank_name = '';
        $this->cheque_number = $this->cheque_date = $this->remarks = '';
        $this->selectedInstallmentId = '';
        $this->payment_mode = 'cash';
    }

    public function render()
    {
        return view('livewire.admin.fees.collect-payment', [
            'unpaidInstallments' => $this->unpaidInstallments,
            'studentResults'     => $this->studentResults,
        ])->layout('layouts.admin', ['title' => 'Collect Payment']);
    }
}
