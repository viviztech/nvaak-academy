<div>
    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Batch</label>
                <select wire:model.live="batchId"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                    <option value="">All Batches</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Subject</label>
                <select wire:model.live="subjectId"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                    <option value="">All Subjects</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Summary progress bars --}}
    @if (!empty($summary))
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Syllabus Completion by Subject</h3>
            <div class="space-y-4">
                @foreach ($summary as $s)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-700">{{ $s['subject'] }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $s['percent'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $s['percent'] >= 75 ? 'bg-green-500' : ($s['percent'] >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                style="width: {{ $s['percent'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $s['covered'] }} / {{ $s['total'] }} topics covered</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Coverage log table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 text-sm">Coverage Log</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Chapter</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Batch</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Faculty</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($coverages as $coverage)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($coverage->taught_date)->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-sm text-gray-800">{{ $coverage->subject?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $coverage->chapter?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $coverage->batch?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-600">{{ $coverage->faculty?->user?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $coverage->is_completed ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                {{ $coverage->is_completed ? 'Completed' : 'In Progress' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-sm">
                            No coverage records found. Faculty log syllabus coverage from their portal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($coverages->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">{{ $coverages->links() }}</div>
        @endif
    </div>
</div>
