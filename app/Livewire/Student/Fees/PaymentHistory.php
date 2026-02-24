<?php

namespace App\Livewire\Student\Fees;

use Livewire\Component;
use App\Models\FeePayment;
use Illuminate\Support\Facades\Auth;

class PaymentHistory extends Component
{
    public function getPaymentsProperty()
    {
        $user    = Auth::user();
        $student = $user->student ?? null;

        if (! $student) {
            return collect();
        }

        return FeePayment::with(['feeAssignment.feeStructure'])
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->orderByDesc('payment_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.student.fees.payment-history', [
            'payments' => $this->payments,
        ])->layout('layouts.student', ['title' => 'Payment History']);
    }
}
