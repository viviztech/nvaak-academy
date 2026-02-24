<div>
    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Today's Collection</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($todayTotal, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ today()->format('d M Y') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">This Month</p>
            <p class="text-2xl font-bold text-green-600 mt-2">₹{{ number_format($monthTotal, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Records</p>
            <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $payments->total() }}</p>
            <p class="text-xs text-gray-400 mt-1">Filtered results</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
        <div class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-48 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search student or receipt..."
                       class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">From:</label>
                <input wire:model.live="dateFrom" type="date"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">To:</label>
                <input wire:model.live="dateTo" type="date"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <select wire:model.live="modeFilter"
                    class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                <option value="">All Modes</option>
                <option value="cash">Cash</option>
                <option value="upi">UPI</option>
                <option value="cheque">Cheque</option>
                <option value="dd">DD</option>
                <option value="online">Online</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt #</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mode</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($payments as $payment)
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
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $payment->student->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $payment->feeAssignment->batch->name ?? '—' }}</td>
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
                               class="text-indigo-600 hover:text-indigo-800 text-xs font-medium px-2 py-1 rounded hover:bg-indigo-50">
                                Download
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <p class="font-medium">No payment records found</p>
                            <p class="text-xs mt-1">Adjust your filters to see results</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($payments->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
