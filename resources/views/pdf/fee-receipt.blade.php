<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $payment->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; }
        .page { width: 100%; max-width: 560px; margin: 0 auto; padding: 30px 28px; }

        /* Header */
        .header { text-align: center; border-bottom: 3px solid #1e3a5f; padding-bottom: 18px; margin-bottom: 16px; }
        .institute-name { font-size: 22px; font-weight: 700; color: #1e3a5f; letter-spacing: 1px; }
        .institute-tagline { font-size: 10px; color: #f97316; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
        .institute-contact { font-size: 10px; color: #555; margin-top: 6px; line-height: 1.5; }

        /* Receipt Title */
        .receipt-title-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px; background: #f97316; padding: 10px 14px; border-radius: 6px; }
        .receipt-title { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: 2px; }
        .receipt-meta { text-align: right; color: #fff; font-size: 10px; }
        .receipt-meta .label { opacity: 0.85; }
        .receipt-meta .value { font-weight: 700; font-size: 12px; }

        /* Sections */
        .section-title { font-size: 10px; font-weight: 700; color: #1e3a5f; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }

        /* Student Details Box */
        .details-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; margin-bottom: 16px; background: #f9fafb; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .detail-item .detail-label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .detail-item .detail-value { font-size: 12px; font-weight: 600; color: #1a1a1a; margin-top: 1px; }

        /* Fee Table */
        .fee-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .fee-table th { background: #1e3a5f; color: #fff; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .fee-table th.right { text-align: right; }
        .fee-table td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; font-size: 12px; }
        .fee-table td.right { text-align: right; }
        .fee-table tr.total-row td { font-weight: 700; background: #f0f7ff; border-top: 2px solid #1e3a5f; }
        .fee-table tr.concession-row td { color: #16a34a; }

        /* Payment Info */
        .payment-info-row { display: flex; gap: 16px; margin-bottom: 16px; }
        .payment-info-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; }
        .payment-info-box .pi-label { font-size: 9px; color: #888; text-transform: uppercase; }
        .payment-info-box .pi-value { font-size: 12px; font-weight: 600; color: #1a1a1a; margin-top: 2px; }

        /* Amount in Words */
        .amount-words { background: #fffbf5; border: 1px solid #fed7aa; border-radius: 6px; padding: 10px 12px; margin-bottom: 16px; }
        .amount-words .aw-label { font-size: 9px; color: #c2410c; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
        .amount-words .aw-text { font-size: 12px; font-weight: 700; color: #1a1a1a; margin-top: 3px; }

        /* Footer */
        .footer { border-top: 1px dashed #ccc; padding-top: 14px; display: flex; justify-content: space-between; align-items: flex-end; }
        .footer-note { font-size: 9px; color: #888; max-width: 60%; }
        .stamp-area { text-align: center; width: 100px; }
        .stamp-box { border: 1px dashed #bbb; height: 50px; border-radius: 4px; margin-bottom: 4px; }
        .stamp-label { font-size: 8px; color: #aaa; }

        /* Watermark for paid */
        .paid-watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-35deg); font-size: 80px; font-weight: 900; color: rgba(22, 163, 74, 0.06); letter-spacing: 8px; pointer-events: none; }

        .divider { height: 1px; background: #e5e7eb; margin: 14px 0; }
    </style>
</head>
<body>
<div class="page" style="position: relative;">
    <div class="paid-watermark">PAID</div>

    {{-- Institute Header --}}
    <div class="header">
        <div class="institute-name">NVAAK ACADEMY</div>
        <div class="institute-tagline">Excellence in Education</div>
        <div class="institute-contact">
            123, Education Street, Chennai, Tamil Nadu - 600001<br>
            Phone: +91-98765-43210 &nbsp;|&nbsp; Email: info@nvaakacademy.com &nbsp;|&nbsp; www.nvaakacademy.com
        </div>
    </div>

    {{-- Receipt Title + Number --}}
    <div class="receipt-title-row">
        <div class="receipt-title">FEE RECEIPT</div>
        <div class="receipt-meta">
            <div class="label">Receipt No.</div>
            <div class="value">{{ $payment->receipt_number }}</div>
            <div class="label" style="margin-top:4px;">Date</div>
            <div class="value">{{ $payment->payment_date->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Student Details --}}
    <div class="section-title">Student Information</div>
    <div class="details-box">
        <div class="details-grid">
            <div class="detail-item">
                <div class="detail-label">Student Name</div>
                <div class="detail-value">{{ $payment->student->user->name ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Student Code</div>
                <div class="detail-value">{{ $payment->student->student_code ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Batch</div>
                <div class="detail-value">{{ $payment->feeAssignment->batch->name ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Phone</div>
                <div class="detail-value">{{ $payment->student->user->phone ?? $payment->student->parent_phone ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Fee Details Table --}}
    <div class="section-title">Fee Details</div>
    <table class="fee-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment->feeAssignment->feeStructure->name ?? 'Fee' }}
                    @if ($payment->installment)
                        <br><span style="font-size:10px; color:#888;">{{ $payment->installment->name }}</span>
                    @endif
                </td>
                <td class="right">{{ number_format($payment->feeAssignment->final_amount, 2) }}</td>
            </tr>
            @if ($payment->feeAssignment->concession_amount > 0)
                <tr class="concession-row">
                    <td>Concession ({{ ucfirst(str_replace('_', '/', $payment->feeAssignment->concession_type)) }})</td>
                    <td class="right">- {{ number_format($payment->feeAssignment->concession_amount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Amount Paid This Receipt</td>
                <td class="right">{{ number_format($payment->amount_paid, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Payment Info --}}
    <div class="section-title">Payment Information</div>
    <div class="payment-info-row">
        <div class="payment-info-box">
            <div class="pi-label">Payment Mode</div>
            <div class="pi-value">{{ strtoupper($payment->payment_mode) }}</div>
        </div>
        <div class="payment-info-box">
            <div class="pi-label">Payment Date</div>
            <div class="pi-value">{{ $payment->payment_date->format('d M Y') }}</div>
        </div>
        <div class="payment-info-box">
            <div class="pi-label">
                @if ($payment->payment_mode === 'cheque') Cheque No.
                @elseif (in_array($payment->payment_mode, ['upi', 'online'])) Transaction ID
                @elseif ($payment->payment_mode === 'dd') DD Number
                @else Reference
                @endif
            </div>
            <div class="pi-value">
                {{ $payment->cheque_number ?? $payment->transaction_reference ?? $payment->razorpay_payment_id ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Amount in Words --}}
    @php
        $amountInt = (int) $payment->amount_paid;
        $amountFmt = number_format($payment->amount_paid, 2);
    @endphp
    <div class="amount-words">
        <div class="aw-label">Amount in Words</div>
        <div class="aw-text">Rupees {{ ucwords(\App\Helpers\NumberToWords::convert($amountInt) ?? number_format($amountInt)) }} Only &mdash; (₹{{ $amountFmt }})</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-note">
            This is a computer generated receipt and is valid without signature.<br>
            For queries, contact the accounts department at accounts@nvaakacademy.com
        </div>
        <div class="stamp-area">
            <div class="stamp-box"></div>
            <div class="stamp-label">Authorized Signatory</div>
        </div>
    </div>
</div>
</body>
</html>
