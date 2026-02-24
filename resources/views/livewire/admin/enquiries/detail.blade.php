<div x-data="{ showFollowUpModal: @entangle('showFollowUpModal') }">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.enquiries.index') }}"
               class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $enquiry->first_name }} {{ $enquiry->last_name }}
                </h2>
                <p class="mt-0.5 text-sm text-gray-500">Enquiry Details</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showFollowUpModal = true"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Follow-up
            </button>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-10 gap-6">

        <!-- Left: 70% -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Flash message inline -->
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Enquiry Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Enquiry Details</h3>
                    @php
                        $statusColors = [
                            'new'            => 'bg-blue-100 text-blue-800',
                            'contacted'      => 'bg-yellow-100 text-yellow-800',
                            'interested'     => 'bg-green-100 text-green-800',
                            'not_interested' => 'bg-red-100 text-red-800',
                            'converted'      => 'bg-emerald-100 text-emerald-800',
                            'lost'           => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$enquiry->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $enquiry->first_name }} {{ $enquiry->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->phone }}</dd>
                        </div>
                        @if($enquiry->alternate_phone)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Alternate Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->alternate_phone }}</dd>
                        </div>
                        @endif
                        @if($enquiry->email)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->email }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Source</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $enquiry->source ?? '—') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Course Interest</dt>
                            <dd class="mt-1 text-sm text-gray-900 uppercase font-medium">{{ $enquiry->course_interest ?? '—' }}</dd>
                        </div>
                        @if($enquiry->gender)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $enquiry->gender }}</dd>
                        </div>
                        @endif
                        @if($enquiry->date_of_birth)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($enquiry->date_of_birth)->format('d M Y') }}</dd>
                        </div>
                        @endif
                        @if($enquiry->city || $enquiry->state)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ collect([$enquiry->city, $enquiry->state])->filter()->join(', ') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</dt>
                            <dd class="mt-1">
                                @php
                                    $priorityColors = ['high' => 'bg-red-100 text-red-700', 'medium' => 'bg-orange-100 text-orange-700', 'low' => 'bg-green-100 text-green-700'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $priorityColors[$enquiry->priority ?? 'medium'] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($enquiry->priority ?? 'medium') }}
                                </span>
                            </dd>
                        </div>
                        @if($enquiry->batch_interest)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Preference</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->batch_interest }}</dd>
                        </div>
                        @endif
                        @if($enquiry->academic_background)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Background</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->academic_background }}</dd>
                        </div>
                        @endif
                        @if($enquiry->previous_marks)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Marks / Score</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->previous_marks }}</dd>
                        </div>
                        @endif
                        @if($enquiry->current_school_college)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Current School / College</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->current_school_college }}</dd>
                        </div>
                        @endif
                        @if($enquiry->referral_name)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Referred By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->referral_name }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->assignedTo?->name ?? '— Unassigned —' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $enquiry->created_at->format('d M Y, h:i A') }}</dd>
                        </div>
                    </div>

                    @if($enquiry->query_notes)
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Notes / Query</dt>
                            <dd class="text-sm text-gray-700 whitespace-pre-line">{{ $enquiry->query_notes }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Follow-up Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Follow-up History</h3>
                    <span class="text-xs text-gray-500">{{ $enquiry->followUps->count() }} entries</span>
                </div>
                <div class="p-6">
                    @if($enquiry->followUps->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm text-gray-500">No follow-ups recorded yet.</p>
                            <button @click="showFollowUpModal = true"
                                    class="mt-3 text-sm text-indigo-600 hover:underline">
                                Add the first follow-up
                            </button>
                        </div>
                    @else
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($enquiry->followUps->sortByDesc('followed_up_at') as $followUp)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <!-- Icon -->
                                                <div>
                                                    @php
                                                        $typeIcons = [
                                                            'call'      => '📞',
                                                            'email'     => '📧',
                                                            'whatsapp'  => '💬',
                                                            'in_person' => '🤝',
                                                            'sms'       => '📱',
                                                        ];
                                                        $outcomeColors = [
                                                            'interested'     => 'bg-green-100 text-green-700',
                                                            'not_interested' => 'bg-red-100 text-red-700',
                                                            'callback'       => 'bg-yellow-100 text-yellow-700',
                                                            'no_response'    => 'bg-gray-100 text-gray-600',
                                                            'converted'      => 'bg-emerald-100 text-emerald-700',
                                                        ];
                                                    @endphp
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 ring-4 ring-white text-sm">
                                                        {{ $typeIcons[$followUp->follow_up_type] ?? '📋' }}
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-2 mb-1">
                                                            <span class="text-sm font-medium text-gray-900 capitalize">
                                                                {{ str_replace('_', ' ', $followUp->follow_up_type) }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $outcomeColors[$followUp->outcome] ?? 'bg-gray-100 text-gray-600' }}">
                                                                {{ ucfirst(str_replace('_', ' ', $followUp->outcome)) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-600">{{ $followUp->notes }}</p>
                                                        @if($followUp->next_follow_up_at)
                                                            <p class="text-xs text-indigo-600 mt-1">
                                                                Next follow-up: {{ \Carbon\Carbon::parse($followUp->next_follow_up_at)->format('d M Y, h:i A') }}
                                                            </p>
                                                        @endif
                                                        @if($followUp->createdBy)
                                                            <p class="text-xs text-gray-400 mt-1">By {{ $followUp->createdBy->name }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-xs text-gray-500 pt-0.5">
                                                        {{ \Carbon\Carbon::parse($followUp->followed_up_at)->format('d M Y') }}<br>
                                                        {{ \Carbon\Carbon::parse($followUp->followed_up_at)->format('h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: 30% Actions Panel -->
        <div class="lg:col-span-3 space-y-4">

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Quick Actions</h3>
                </div>
                <div class="p-4 space-y-3">
                    <button @click="showFollowUpModal = true"
                            class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Follow-up
                    </button>

                    @if($enquiry->status !== 'converted')
                        <button wire:click="convertToAdmission"
                                wire:confirm="Convert this enquiry to an admission application?"
                                class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 rounded-md hover:bg-emerald-100 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Convert to Admission
                        </button>
                    @endif
                </div>
            </div>

            <!-- Change Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Change Status</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'interested' => 'Interested', 'not_interested' => 'Not Int.', 'lost' => 'Lost'] as $value => $label)
                            <button wire:click="updateStatus('{{ $value }}')"
                                    class="px-2 py-1.5 text-xs font-medium rounded border transition
                                        {{ $enquiry->status === $value
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Assign To -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Assign To</h3>
                </div>
                <div class="p-4">
                    <select wire:model="newAssignedTo"
                            wire:change="assignTo($event.target.value)"
                            class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Unassigned —</option>
                        @foreach($this->staffUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Enquiry Meta -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Meta</h3>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Created</span>
                        <span class="text-gray-800">{{ $enquiry->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Updated</span>
                        <span class="text-gray-800">{{ $enquiry->updated_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Follow-ups</span>
                        <span class="text-gray-800">{{ $enquiry->followUps->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up Modal -->
    <div x-show="showFollowUpModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="showFollowUpModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showFollowUpModal = false"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showFollowUpModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Add Follow-up</h3>
                        <button @click="showFollowUpModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="addFollowUp" class="space-y-4">
                        <!-- Follow-up Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="follow_up_type"
                                    class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="call">Phone Call</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="in_person">In Person</option>
                                <option value="sms">SMS</option>
                            </select>
                            @error('follow_up_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Notes <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="notes"
                                      rows="3"
                                      placeholder="What was discussed during this follow-up..."
                                      class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Outcome -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Outcome <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="outcome"
                                    class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="no_response">No Response</option>
                                <option value="callback">Will Callback</option>
                                <option value="interested">Interested</option>
                                <option value="not_interested">Not Interested</option>
                                <option value="converted">Converted</option>
                            </select>
                            @error('outcome') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Next Follow-up -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Next Follow-up</label>
                            <input wire:model="next_follow_up_at"
                                   type="datetime-local"
                                   class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"/>
                            @error('next_follow_up_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Mark as Converted -->
                        <div class="flex items-center">
                            <input wire:model="is_converted"
                                   id="is_converted"
                                   type="checkbox"
                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"/>
                            <label for="is_converted" class="ml-2 text-sm font-medium text-gray-700">
                                Mark enquiry as Converted
                            </label>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-2">
                            <button type="button"
                                    @click="showFollowUpModal = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition disabled:opacity-70">
                                <span wire:loading.remove wire:target="addFollowUp">Save Follow-up</span>
                                <span wire:loading wire:target="addFollowUp" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
