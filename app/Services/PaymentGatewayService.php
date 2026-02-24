<?php

namespace App\Services;

use App\Models\FeePayment;
use App\Models\StudentFeeAssignment;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    public function createOrder(float $amount, array $notes = []): array
    {
        try {
            $api   = new \Razorpay\Api\Api(config('services.razorpay.key', ''), config('services.razorpay.secret', ''));
            $order = $api->order->create([
                'amount'   => (int) ($amount * 100),
                'currency' => 'INR',
                'notes'    => $notes,
            ]);

            return [
                'success'  => true,
                'order_id' => $order->id,
                'amount'   => $amount,
                'key'      => config('services.razorpay.key', ''),
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay order error: ' . $e->getMessage());

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $api = new \Razorpay\Api\Api(config('services.razorpay.key', ''), config('services.razorpay.secret', ''));
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature'  => $signature,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function recordPayment(StudentFeeAssignment $assignment, array $data): FeePayment
    {
        return FeePayment::create([
            'institute_id'        => $assignment->student->institute_id,
            'student_id'          => $assignment->student_id,
            'fee_assignment_id'   => $assignment->id,
            'installment_id'      => $data['installment_id'] ?? null,
            'receipt_number'      => FeePayment::generateReceiptNumber(),
            'payment_date'        => now()->toDateString(),
            'amount_paid'         => $data['amount'],
            'payment_mode'        => $data['payment_mode'] ?? 'online',
            'razorpay_order_id'   => $data['razorpay_order_id'] ?? null,
            'razorpay_payment_id' => $data['razorpay_payment_id'] ?? null,
            'razorpay_signature'  => $data['razorpay_signature'] ?? null,
            'status'              => 'completed',
        ]);
    }
}
