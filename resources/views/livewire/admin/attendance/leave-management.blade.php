<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Leave Management</h2>
            <p class="text-sm text-gray-500 mt-0.5">Review and manage student leave requests</p>
        </div>
    </div>

    {{-- Tab Bar --}}
    <div class="flex gap-1 mb-4 bg-gray-100 p-1 rounded-xl w-fit">
        @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
            <button wire:click="setStatusFilter('{{ $val }}')"
                    class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors
                           {{ $statusFilter === $val ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Batch Filter --}}
    <div class="mb-4">
        <select wire:model.live="batchFilter"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
            <option value="">All Batches</option>
            @foreach ($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Days</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
                    @if ($statusFilter === 'pending')
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($leaves as $leave)
                    @php
                        $typeColors = [
                            'medical'  => 'bg-red-100 text-red-700',
                            'personal' => 'bg-yellow-100 text-yellow-700',
                            'family'   => 'bg-blue-100 text-blue-700',
                            'other'    => 'bg-gray-100 text-gray-700',
                        ];
                        $statusColors = [
                            'pending'  => 'bg-yellow-100 text-yellow-700',
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $leave->student->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $leave->batch->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$leave->leave_type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($leave->leave_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $leave->from_date->format('d M') }} – {{ $leave->to_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $leave->total_days }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs">
                            <p class="truncate" title="{{ $leave->reason }}">{{ $leave->reason }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$leave->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $leave->applied_at->format('d M Y') }}
                        </td>
                        @if ($statusFilter === 'pending')
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="approve({{ $leave->id }})"
                                            class="px-3 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                                        Approve
                                    </button>
                                    <button wire:click="reject({{ $leave->id }})"
                                            class="px-3 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $statusFilter === 'pending' ? 9 : 8 }}" class="px-4 py-12 text-center text-gray-400">
                            <p class="font-medium">No {{ $statusFilter }} leave requests</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($leaves->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>
</div>
