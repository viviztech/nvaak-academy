<div>
    {{-- Flash messages --}}
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Students</p>
            <p class="text-3xl font-bold text-navy-700 mt-1">{{ $totalStudents }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Active</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeStudents }}</p>
        </div>
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Inactive</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $inactiveStudents }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by name, code, or phone..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
            </div>
            <div>
                <select wire:model.live="batchFilter"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                    <option value="">All Batches</option>
                    @foreach ($allBatches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="statusFilter"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Batches</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-[#1E3A5F] flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $student->user?->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->user?->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-700">{{ $student->student_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($student->batches->take(2) as $batch)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700">
                                        {{ $batch->name }}
                                    </span>
                                @endforeach
                                @if ($student->batches->count() > 2)
                                    <span class="text-xs text-gray-400">+{{ $student->batches->count() - 2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $student->target_exam === 'neet' ? 'bg-purple-50 text-purple-700' : 'bg-orange-50 text-orange-700' }}">
                                {{ strtoupper($student->target_exam ?? 'NEET') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $student->user?->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                {{ $student->user?->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="toggleActive({{ $student->id }})"
                                    class="text-xs px-3 py-1 rounded-lg border
                                        {{ $student->user?->is_active
                                            ? 'border-red-300 text-red-600 hover:bg-red-50'
                                            : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                                    {{ $student->user?->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button wire:click="deleteStudent({{ $student->id }})"
                                    wire:confirm="Are you sure you want to remove this student?"
                                    class="text-xs px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                                    Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-sm">No students found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($students->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
