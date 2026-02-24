<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Fee Defaulters</h2>
            <p class="text-sm text-gray-500 mt-0.5">Students with outstanding fee payments</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-wrap gap-3">
        <select wire:model.live="batchFilter"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
            <option value="">All Batches</option>
            @foreach ($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
            @endforeach
        </select>
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 font-medium whitespace-nowrap">As of:</label>
            <input wire:model.live="asOfDate" type="date"
                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
        </div>
    </div>

    {{-- Summary --}}
    @if ($defaulters->isNotEmpty())
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Total Defaulters</p>
                <p class="text-3xl font-bold text-red-700 mt-2">{{ $defaulters->count() }}</p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Total Outstanding</p>
                <p class="text-3xl font-bold text-red-700 mt-2">
                    ₹{{ number_format($defaulters->sum(fn($d) => $d->balance), 2) }}
                </p>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fee Structure</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Fee</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Paid</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Balance</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Overdue Days</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($defaulters as $assignment)
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $assignment->student->user->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 font-mono text-xs">
                            {{ $assignment->student->student_code ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $assignment->batch->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $assignment->feeStructure->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">₹{{ number_format($assignment->final_amount, 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-600">₹{{ number_format($assignment->total_paid, 2) }}</td>
                        <td class="px-4 py-3 text-right font-bold text-red-600">₹{{ number_format($assignment->balance, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $days = $this->getOverdueDays($assignment); @endphp
                            @if ($days > 0)
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">{{ $days }}d</span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-medium">No defaulters found</p>
                            <p class="text-xs mt-1">All students are up to date with payments</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
