<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Payment History</h2>
        <p class="text-sm text-gray-500 mt-0.5">All your recorded fee payments</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @if ($payments->isEmpty())
            <div class="px-4 py-12 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v13a2 2 0 01-2 2h-3"/>
                </svg>
                <p class="font-medium">No payment records</p>
                <p class="text-xs mt-1">Your payments will appear here once recorded.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt #</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mode</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($payments as $payment)
                        @php
                            $modeColors = [
                                'cash'   => 'bg-green-100 text-green-700',
                                'upi'    => 'bg-purple-100 text-purple-700',
                                'cheque' => 'bg-yellow-100 text-yellow-700',
                                'dd'     => 'bg-blue-100 text-blue-700',
                                'online' => 'bg-indigo-100 text-indigo-700',
                            ];
                            $statusColors = [
                                'completed' => 'bg-green-100 text-green-700',
                                'pending'   => 'bg-yellow-100 text-yellow-700',
                                'failed'    => 'bg-red-100 text-red-700',
                                'refunded'  => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-700 font-medium">{{ $payment->receipt_number }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $payment->payment_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-gray-900">
                                {{ $payment->feeAssignment->feeStructure->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">₹{{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $modeColors[$payment->payment_mode] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ strtoupper($payment->payment_mode) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.fees.receipt', $payment->id) }}"
                                   target="_blank"
                                   class="text-indigo-600 hover:text-indigo-800 text-xs font-medium px-2 py-1 rounded hover:bg-indigo-50">
                                    Download
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
