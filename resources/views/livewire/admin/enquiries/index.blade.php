<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Enquiries</h2>
            <p class="mt-1 text-sm text-gray-500">Manage all leads and enquiries</p>
        </div>
        <a href="{{ route('admin.enquiries.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Enquiry
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Enquiries</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $this->totalCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">New Today</p>
            <p class="mt-1 text-3xl font-bold text-blue-600">{{ $this->newTodayCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Converted</p>
            <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $this->convertedCount }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Follow-ups Due Today</p>
            <p class="mt-1 text-3xl font-bold text-orange-600">{{ $this->followUpsDueCount }}</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search name, phone, email..."
                       class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
            </div>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter"
                    class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="contacted">Contacted</option>
                <option value="interested">Interested</option>
                <option value="not_interested">Not Interested</option>
                <option value="converted">Converted</option>
                <option value="lost">Lost</option>
            </select>

            <!-- Source Filter -->
            <select wire:model.live="sourceFilter"
                    class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Sources</option>
                <option value="walk_in">Walk In</option>
                <option value="phone">Phone</option>
                <option value="website">Website</option>
                <option value="social">Social Media</option>
                <option value="referral">Referral</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortByColumn('first_name')"
                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center space-x-1 hover:text-gray-700">
                                <span>Name</span>
                                @if($sortBy === 'first_name')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDir === 'asc')
                                            <path d="M5 10l5-5 5 5H5z"/>
                                        @else
                                            <path d="M15 10l-5 5-5-5h10z"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortByColumn('created_at')"
                                    class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center space-x-1 hover:text-gray-700">
                                <span>Created</span>
                                @if($sortBy === 'created_at')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        @if($sortDir === 'asc')
                                            <path d="M5 10l5-5 5 5H5z"/>
                                        @else
                                            <path d="M15 10l-5 5-5-5h10z"/>
                                        @endif
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($this->enquiries as $enquiry)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <a href="{{ route('admin.enquiries.show', $enquiry->id) }}"
                                       class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                        {{ $enquiry->first_name }} {{ $enquiry->last_name }}
                                    </a>
                                    @if($enquiry->email)
                                        <span class="text-xs text-gray-400">{{ $enquiry->email }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $enquiry->phone }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="capitalize">{{ $enquiry->course_interest ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span class="capitalize">{{ str_replace('_', ' ', $enquiry->source ?? '—') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'new'            => 'bg-blue-100 text-blue-800',
                                        'contacted'      => 'bg-yellow-100 text-yellow-800',
                                        'interested'     => 'bg-green-100 text-green-800',
                                        'not_interested' => 'bg-red-100 text-red-800',
                                        'converted'      => 'bg-emerald-100 text-emerald-800',
                                        'lost'           => 'bg-gray-100 text-gray-800',
                                    ];
                                    $statusColor = $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $priorityColors = [
                                        'high'   => 'bg-red-100 text-red-700',
                                        'medium' => 'bg-orange-100 text-orange-700',
                                        'low'    => 'bg-green-100 text-green-700',
                                    ];
                                    $priorityColor = $priorityColors[$enquiry->priority ?? 'medium'] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColor }}">
                                    {{ ucfirst($enquiry->priority ?? 'medium') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $enquiry->assignedTo?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $enquiry->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <!-- View -->
                                    <a href="{{ route('admin.enquiries.show', $enquiry->id) }}"
                                       title="View"
                                       class="text-gray-400 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    @if($enquiry->status !== 'converted')
                                        <!-- Convert -->
                                        <button wire:click="convertToAdmission({{ $enquiry->id }})"
                                                wire:confirm="Convert this enquiry to an admission application?"
                                                title="Convert to Admission"
                                                class="text-gray-400 hover:text-emerald-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Delete -->
                                    <button wire:click="deleteEnquiry({{ $enquiry->id }})"
                                            wire:confirm="Are you sure you want to delete this enquiry? This action cannot be undone."
                                            title="Delete"
                                            class="text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-sm text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                No enquiries found.
                                @if($search || $statusFilter || $sourceFilter)
                                    <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('sourceFilter', '')"
                                            class="ml-2 text-indigo-600 hover:underline">Clear filters</button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($this->enquiries->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $this->enquiries->links() }}
            </div>
        @endif
    </div>
</div>
