<div>
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
                <h2 class="text-2xl font-bold text-gray-900">Add Enquiry</h2>
                <p class="mt-0.5 text-sm text-gray-500">Capture a new lead or enquiry</p>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">

        <!-- Section 1: Personal Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Personal Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Lead Source <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="source"
                            class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="walk_in">Walk In</option>
                        <option value="phone">Phone Call</option>
                        <option value="website">Website</option>
                        <option value="social">Social Media</option>
                        <option value="referral">Referral</option>
                    </select>
                    @error('source') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select wire:model="gender"
                            class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="first_name"
                           type="text"
                           placeholder="Enter first name"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="last_name"
                           type="text"
                           placeholder="Enter last name"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input wire:model="email"
                           type="email"
                           placeholder="student@example.com"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="phone"
                           type="text"
                           placeholder="+91 98765 43210"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Alternate Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alternate Phone</label>
                    <input wire:model="alternate_phone"
                           type="text"
                           placeholder="Alternate contact number"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('alternate_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input wire:model="date_of_birth"
                           type="date"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('date_of_birth') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input wire:model="city"
                           type="text"
                           placeholder="City"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- State -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                    <input wire:model="state"
                           type="text"
                           placeholder="State"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('state') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Academic Interest -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Academic Interest</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Course Interest -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Course Interest <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="course_interest" type="radio" value="neet"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"/>
                            <span class="text-sm font-medium text-gray-700">NEET</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="course_interest" type="radio" value="ias"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"/>
                            <span class="text-sm font-medium text-gray-700">IAS</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="course_interest" type="radio" value="both"
                                   class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"/>
                            <span class="text-sm font-medium text-gray-700">Both</span>
                        </label>
                    </div>
                    @error('course_interest') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Batch Interest -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batch Preference</label>
                    <input wire:model="batch_interest"
                           type="text"
                           placeholder="e.g. Morning batch, Weekend batch"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('batch_interest') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Academic Background -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Background</label>
                    <input wire:model="academic_background"
                           type="text"
                           placeholder="e.g. 11th Science, 12th completed"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('academic_background') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Previous Marks -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Previous Exam Marks / Score</label>
                    <input wire:model="previous_marks"
                           type="text"
                           placeholder="e.g. 85%, 450/720"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('previous_marks') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Current School/College -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current School / College</label>
                    <input wire:model="current_school_college"
                           type="text"
                           placeholder="Name of current institution"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('current_school_college') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Additional Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Additional Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Query Notes -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Query / Notes</label>
                    <textarea wire:model="query_notes"
                              rows="3"
                              placeholder="Any specific queries or notes about this lead..."
                              class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                    @error('query_notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Referral Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referral Name</label>
                    <input wire:model="referral_name"
                           type="text"
                           placeholder="Who referred this student?"
                           class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"/>
                    @error('referral_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                    <select wire:model="assigned_to"
                            class="block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Unassigned —</option>
                        @foreach($this->staffUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Priority -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="priority" type="radio" value="high"
                                   class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"/>
                            <span class="text-sm font-medium text-red-700">High</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="priority" type="radio" value="medium"
                                   class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500"/>
                            <span class="text-sm font-medium text-orange-700">Medium</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input wire:model="priority" type="radio" value="low"
                                   class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"/>
                            <span class="text-sm font-medium text-green-700">Low</span>
                        </label>
                    </div>
                    @error('priority') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.enquiries.index') }}"
               class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-5 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-70">
                <span wire:loading.remove>Save Enquiry</span>
                <span wire:loading class="flex items-center">
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
