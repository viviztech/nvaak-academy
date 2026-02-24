<div>
    {{-- ── Stats Grid ─────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Enquiries --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#1e3a5f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_enquiries']) }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Total Enquiries</p>
                @if($stats['new_enquiries'] > 0)
                    <span class="inline-block mt-1 text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">
                        {{ $stats['new_enquiries'] }} new
                    </span>
                @endif
            </div>
        </div>

        {{-- Active Students --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_students']) }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Active Students</p>
                <span class="inline-block mt-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                    {{ $stats['total_batches'] }} batches
                </span>
            </div>
        </div>

        {{-- Fees This Month --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-2xl font-bold text-gray-900">&#8377;{{ number_format($stats['fees_this_month']) }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Fees This Month</p>
                @if($stats['pending_admissions'] > 0)
                    <span class="inline-block mt-1 text-xs font-medium text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full">
                        {{ $stats['pending_admissions'] }} pending admissions
                    </span>
                @endif
            </div>
        </div>

        {{-- Upcoming Exams --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['upcoming_exams']) }}</p>
                <p class="text-sm text-gray-500 mt-0.5">Upcoming Exams</p>
                <a href="{{ route('admin.exams.index') }}"
                   class="inline-block mt-1 text-xs font-medium text-purple-600 hover:text-purple-800">
                    View all &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- ── Bottom Grid ────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Enquiries (2/3 width) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Recent Enquiries</h2>
                <a href="{{ route('admin.enquiries.index') }}"
                   class="text-sm font-medium text-[#1e3a5f] hover:text-orange-500 transition-colors">
                    View all &rarr;
                </a>
            </div>

            @if($recentEnquiries->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">No enquiries yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="text-left px-6 py-3">Name</th>
                                <th class="text-left px-6 py-3">Phone</th>
                                <th class="text-left px-6 py-3">Course</th>
                                <th class="text-left px-6 py-3">Status</th>
                                <th class="text-left px-6 py-3">Assigned To</th>
                                <th class="text-left px-6 py-3">Date</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentEnquiries as $enquiry)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 font-medium text-gray-900">{{ $enquiry->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $enquiry->phone }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ $enquiry->course_interested ?? '—' }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $statusColors = [
                                                'new'         => 'bg-blue-100 text-blue-700',
                                                'contacted'   => 'bg-yellow-100 text-yellow-700',
                                                'interested'  => 'bg-green-100 text-green-700',
                                                'converted'   => 'bg-purple-100 text-purple-700',
                                                'not_interested' => 'bg-red-100 text-red-700',
                                            ];
                                            $colorClass = $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">{{ $enquiry->assignedTo?->name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-500 whitespace-nowrap">
                                        {{ $enquiry->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('admin.enquiries.detail', $enquiry->id) }}"
                                           class="text-[#1e3a5f] hover:text-orange-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Quick Actions (1/3 width) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Quick Actions</h2>
            </div>
            <div class="p-5 space-y-3">

                <a href="{{ route('admin.enquiries.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-[#1e3a5f] hover:bg-blue-50 transition-all group">
                    <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-[#1e3a5f] transition-colors">
                        <svg class="w-5 h-5 text-[#1e3a5f] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-[#1e3a5f]">Add New Enquiry</span>
                </a>

                <a href="{{ route('admin.fees.collect') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-orange-400 hover:bg-orange-50 transition-all group">
                    <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                        <svg class="w-5 h-5 text-orange-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-orange-600">Collect Fee Payment</span>
                </a>

                <a href="{{ route('admin.attendance.mark') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-green-400 hover:bg-green-50 transition-all group">
                    <div class="w-9 h-9 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-500 transition-colors">
                        <svg class="w-5 h-5 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Mark Attendance</span>
                </a>

                <a href="{{ route('admin.exams.create') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-purple-400 hover:bg-purple-50 transition-all group">
                    <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-500 transition-colors">
                        <svg class="w-5 h-5 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Create Exam</span>
                </a>

                <a href="{{ route('admin.ias.submissions') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-red-400 hover:bg-red-50 transition-all group">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-500 transition-colors">
                        <svg class="w-5 h-5 text-red-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-700">IAS Submissions</span>
                </a>

                <a href="{{ route('admin.fees.defaulters') }}"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-yellow-400 hover:bg-yellow-50 transition-all group">
                    <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-400 transition-colors">
                        <svg class="w-5 h-5 text-yellow-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Fee Defaulters</span>
                </a>
            </div>
        </div>
    </div>
</div>
