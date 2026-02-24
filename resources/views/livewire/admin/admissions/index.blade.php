<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Applications</p>
            <p class="text-3xl font-bold text-[#1E3A5F] mt-1">{{ $totalCount }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Pending Review</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pendingCount }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $approvedCount }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search by name, phone, or application number..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
            </div>
            <select wire:model.live="statusFilter"
                class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="enrolled">Enrolled</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">App. No.</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied On</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($admissions as $admission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $admission->applicant_name }}</p>
                                <p class="text-xs text-gray-500">{{ $admission->applicant_phone }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-700">{{ $admission->application_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $admission->batch?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $admission->created_at?->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'pending'  => 'bg-yellow-50 text-yellow-700',
                                    'approved' => 'bg-green-50 text-green-700',
                                    'rejected' => 'bg-red-50 text-red-600',
                                    'enrolled' => 'bg-blue-50 text-blue-700',
                                ];
                                $color = $colors[$admission->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }}">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($admission->status === 'pending')
                                    <button wire:click="approve({{ $admission->id }})"
                                        class="text-xs px-3 py-1 rounded-lg border border-green-300 text-green-700 hover:bg-green-50">
                                        Approve
                                    </button>
                                    <button wire:click="reject({{ $admission->id }})"
                                        wire:confirm="Reject this admission?"
                                        class="text-xs px-3 py-1 rounded-lg border border-red-300 text-red-600 hover:bg-red-50">
                                        Reject
                                    </button>
                                @endif
                                <a href="{{ route('admissions.detail', $admission->id) }}"
                                    class="text-xs px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-sm">No admissions found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($admissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $admissions->links() }}</div>
        @endif
    </div>
</div>
