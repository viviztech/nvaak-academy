<?php

namespace App\Livewire\Student\Fees;

use Livewire\Component;
use App\Models\StudentFeeAssignment;
use App\Models\FeeInstallment;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Facades\Auth;

class PayFees extends Component
{
    public $assignment;
    public $student;

    public function mount(): void
    {
        $user = Auth::user();
        $this->student = $user->student ?? null;

        if ($this->student) {
            $this->assignment = StudentFeeAssignment::with([
                'feeStructure.installments',
                'payments',
            ])
            ->where('student_id', $this->student->id)
            ->where('is_active', true)
            ->latest()
            ->first();
        }
    }

    public function getInstallmentsProperty()
    {
        if (! $this->assignment) {
            return collect();
        }

        return $this->assignment->feeStructure->installments->map(function ($installment) {
            $paid = $installment->payments()
                ->where('fee_assignment_id', $this->assignment->id)
                ->where('status', 'completed')
                ->exists();

            return [
                'id'         => $installment->id,
                'name'       => $installment->name,
                'amount'     => $installment->amount,
                'due_date'   => $installment->due_date,
                'is_paid'    => $paid,
                'is_overdue' => ! $paid && $installment->due_date->isPast(),
            ];
        });
    }

    public function initiatePayment(int $installmentId): void
    {
        $installment = FeeInstallment::findOrFail($installmentId);
        $service     = app(PaymentGatewayService::class);

        $result = $service->createOrder($installment->amount, [
            'student_id'     => $this->student->id,
            'installment_id' => $installmentId,
        ]);

        if ($result['success']) {
            $this->dispatch('open-razorpay', ...[
                'key'      => $result['key'],
                'amount'   => $result['amount'],
                'order_id' => $result['order_id'],
                'installment_id' => $installmentId,
            ]);
        } else {
            session()->flash('error', 'Payment initiation failed. Please try again.');
        }
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): void
    {
        $service = app(PaymentGatewayService::class);

        if ($service->verifySignature($orderId, $paymentId, $signature)) {
            $service->recordPayment($this->assignment, [
                'amount'              => request('amount') ?? $this->assignment->balance,
                'payment_mode'        => 'online',
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
            ]);

            $this->mount();
            session()->flash('success', 'Payment successful! Your receipt has been generated.');
        } else {
            session()->flash('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function render()
    {
        return view('livewire.student.fees.pay-fees', [
            'installments' => $this->installments,
        ])->layout('layouts.student', ['title' => 'Pay Fees']);
    }
}
