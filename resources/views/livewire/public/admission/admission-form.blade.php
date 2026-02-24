<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Page Header --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold" style="color:#1E3A5F;">Admission Application</h1>
            <p class="text-gray-500 mt-1 text-sm">NVAAK IAS & NEET Academy · Avadi, Chennai</p>
        </div>

        {{-- Flash --}}
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Step Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                {{-- connecting line --}}
                <div class="absolute left-0 right-0 top-4 h-0.5 bg-gray-200 z-0"></div>
                <div class="absolute left-0 top-4 h-0.5 bg-orange-400 z-0 transition-all duration-500"
                     style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>

                @foreach([
                    [1, 'Personal'],
                    [2, 'Academic'],
                    [3, 'Guardian'],
                    [4, 'Documents'],
                    [5, 'Review'],
                ] as [$step, $label])
                <div class="relative z-10 flex flex-col items-center">
                    <button wire:click="goToStep({{ $step }})"
                            @class([
                                'h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all',
                                'text-white border-orange-400' => $currentStep > $step,
                                'text-white border-orange-500' => $currentStep === $step,
                                'text-gray-400 bg-white border-gray-300' => $currentStep < $step,
                            ])
                            @style([
                                'background-color:#F97316' => $currentStep >= $step,
                            ])>
                        @if($currentStep > $step)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $step }}
                        @endif
                    </button>
                    <span class="mt-1.5 text-xs font-medium {{ $currentStep === $step ? 'text-orange-600' : 'text-gray-400' }}">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- ══════════════════════════════════════════════════════════
                 STEP 1: Personal Information
            ══════════════════════════════════════════════════════════ --}}
            @if($currentStep === 1)
            <div class="px-6 py-5 border-b border-gray-100" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                <h2 class="text-base font-bold text-white">Step 1 — Personal Information</h2>
                <p class="text-blue-200 text-xs mt-0.5">Basic personal and contact details</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Name row --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">First Name *</label>
                        <input wire:model="first_name" type="text" placeholder="First name"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Middle Name</label>
                        <input wire:model="middle_name" type="text" placeholder="Middle name"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name *</label>
                        <input wire:model="last_name" type="text" placeholder="Last name"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- DOB / Gender / Blood Group --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Date of Birth *</label>
                        <input wire:model="date_of_birth" type="date"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Gender *</label>
                        <select wire:model="gender"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Blood Group</label>
                        <select wire:model="blood_group"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Phone / Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Phone Number *</label>
                        <input wire:model="phone" type="tel" placeholder="+91 XXXXX XXXXX"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Alternate Phone</label>
                        <input wire:model="alternate_phone" type="tel" placeholder="Alternate number"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                    <input wire:model="email" type="email" placeholder="your@email.com"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Aadhaar --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Aadhaar Number</label>
                    <input wire:model="aadhaar_number" type="text" placeholder="XXXX XXXX XXXX"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                {{-- Address --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Current Address</label>
                        <textarea wire:model="current_address" rows="2" placeholder="Current address"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Permanent Address</label>
                        <textarea wire:model="permanent_address" rows="2" placeholder="Permanent address"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">City</label>
                        <input wire:model="city" type="text" placeholder="City"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">State</label>
                        <input wire:model="state" type="text" placeholder="State"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Postal Code</label>
                        <input wire:model="postal_code" type="text" placeholder="600XXX"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Religion</label>
                        <input wire:model="religion" type="text" placeholder="Religion"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Caste Category</label>
                        <select wire:model="caste_category"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Select</option>
                            <option>General</option>
                            <option>OBC</option>
                            <option>SC</option>
                            <option>ST</option>
                            <option>MBC</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 STEP 2: Academic Background
            ══════════════════════════════════════════════════════════ --}}
            @if($currentStep === 2)
            <div class="px-6 py-5 border-b border-gray-100" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                <h2 class="text-base font-bold text-white">Step 2 — Academic Background</h2>
                <p class="text-blue-200 text-xs mt-0.5">Course preference and academic history</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Course Applied --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Course Applying For *</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['neet' => 'NEET (Medical)', 'ias' => 'IAS / TNPSC', 'both' => 'Both'] as $val => $lbl)
                        <label class="flex items-center gap-2 px-4 py-2.5 border rounded-xl cursor-pointer text-sm transition-colors
                                      {{ $course_applied === $val ? 'border-orange-400 bg-orange-50 text-orange-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="course_applied" value="{{ $val }}" class="sr-only">
                            {{ $lbl }}
                        </label>
                        @endforeach
                    </div>
                    @error('course_applied') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Current School / College</label>
                    <input wire:model="current_school_college" type="text" placeholder="Name of current institution"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Previous Institution</label>
                    <input wire:model="previous_institution" type="text" placeholder="School/college name"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Board</label>
                        <select wire:model="board"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <option value="">Select board</option>
                            <option>CBSE</option>
                            <option>ICSE</option>
                            <option>Tamil Nadu State</option>
                            <option>Other State</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Percentage / CGPA</label>
                        <input wire:model="previous_percentage" type="number" step="0.01" min="0" max="100" placeholder="e.g. 85.5"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('previous_percentage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Year of Passing</label>
                        <input wire:model="year_of_passing" type="number" min="2000" max="{{ date('Y') + 1 }}" placeholder="e.g. 2024"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('year_of_passing') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if(in_array($course_applied, ['neet', 'both']))
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 space-y-4">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Previous NEET Attempt (if any)</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">NEET Score</label>
                            <input wire:model="neet_previous_score" type="number" min="0" max="720" placeholder="Out of 720"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('neet_previous_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">NEET Rank</label>
                            <input wire:model="neet_previous_rank" type="number" min="1" placeholder="All India Rank"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('neet_previous_rank') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 STEP 3: Guardian Information
            ══════════════════════════════════════════════════════════ --}}
            @if($currentStep === 3)
            <div class="px-6 py-5 border-b border-gray-100" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                <h2 class="text-base font-bold text-white">Step 3 — Guardian Information</h2>
                <p class="text-blue-200 text-xs mt-0.5">Parent / guardian contact details</p>
            </div>
            <div class="p-6 space-y-6">

                {{-- Father --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-1 border-b border-gray-100">Father's Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Father's Name *</label>
                            <input wire:model="father_name" type="text" placeholder="Full name"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('father_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Occupation</label>
                            <input wire:model="father_occupation" type="text" placeholder="Occupation"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone *</label>
                            <input wire:model="father_phone" type="tel" placeholder="+91 XXXXX XXXXX"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('father_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                            <input wire:model="father_email" type="email" placeholder="father@email.com"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('father_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Annual Income</label>
                            <input wire:model="father_income" type="text" placeholder="e.g. ₹5,00,000"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    </div>
                </div>

                {{-- Mother --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-1 border-b border-gray-100">Mother's Details</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mother's Name</label>
                            <input wire:model="mother_name" type="text" placeholder="Full name"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Occupation</label>
                            <input wire:model="mother_occupation" type="text" placeholder="Occupation"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <input wire:model="mother_phone" type="tel" placeholder="+91 XXXXX XXXXX"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                            <input wire:model="mother_email" type="email" placeholder="mother@email.com"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    </div>
                </div>

                {{-- Guardian (optional) --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3 pb-1 border-b border-gray-100">Guardian (if different from parents)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Guardian Name</label>
                            <input wire:model="guardian_name" type="text" placeholder="Full name"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Relation</label>
                            <input wire:model="guardian_relation" type="text" placeholder="e.g. Uncle"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <input wire:model="guardian_phone" type="tel" placeholder="+91 XXXXX XXXXX"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 STEP 4: Documents Upload
            ══════════════════════════════════════════════════════════ --}}
            @if($currentStep === 4)
            <div class="px-6 py-5 border-b border-gray-100" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                <h2 class="text-base font-bold text-white">Step 4 — Document Upload</h2>
                <p class="text-blue-200 text-xs mt-0.5">Upload required documents (JPG, PNG or PDF · max 4 MB each)</p>
            </div>
            <div class="p-6 space-y-5">

                @foreach([
                    ['photo', 'Passport Photo', 'JPG/PNG, max 2 MB', 'image'],
                    ['aadhaar_document', 'Aadhaar Card', 'JPG/PNG/PDF, max 4 MB', 'file'],
                    ['marksheet', '10th/12th Marksheet', 'JPG/PNG/PDF, max 4 MB', 'file'],
                ] as [$field, $label, $hint, $type])
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-orange-300 transition-colors">
                        <input wire:model="{{ $field }}" type="file"
                               {{ $type === 'image' ? 'accept="image/*"' : 'accept=".pdf,.jpg,.jpeg,.png"' }}
                               class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:text-white file:cursor-pointer"
                               style="--tw-file-selector-button-bg: #F97316">
                        <p class="text-xs text-gray-400 mt-1">{{ $hint }}</p>
                        @if($this->$field)
                            <p class="text-xs text-green-600 mt-1 font-medium">✓ File selected</p>
                        @endif
                    </div>
                    @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach

                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-800">
                    <p class="font-semibold mb-1">Documents Required:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Recent passport-size photograph (white background)</li>
                        <li>Aadhaar card (front & back)</li>
                        <li>Latest marksheet (10th or 12th)</li>
                    </ul>
                    <p class="mt-2 text-yellow-600">All documents are optional at this stage and can be submitted at the centre.</p>
                </div>
            </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 STEP 5: Review & Submit
            ══════════════════════════════════════════════════════════ --}}
            @if($currentStep === 5)
            <div class="px-6 py-5 border-b border-gray-100" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                <h2 class="text-base font-bold text-white">Step 5 — Review & Submit</h2>
                <p class="text-blue-200 text-xs mt-0.5">Please review your details before submitting</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Summary sections --}}
                @foreach([
                    ['Personal', [
                        ['Name', trim("$first_name $middle_name $last_name")],
                        ['Date of Birth', $date_of_birth],
                        ['Gender', ucfirst($gender)],
                        ['Phone', $phone],
                        ['Email', $email ?: '—'],
                        ['City', $city ?: '—'],
                    ]],
                    ['Academic', [
                        ['Course Applied', strtoupper($course_applied)],
                        ['Previous Institution', $previous_institution ?: '—'],
                        ['Board', $board ?: '—'],
                        ['Percentage', $previous_percentage ? $previous_percentage.'%' : '—'],
                        ['Year of Passing', $year_of_passing ?: '—'],
                    ]],
                    ['Guardian', [
                        ['Father Name', $father_name],
                        ['Father Phone', $father_phone],
                        ['Mother Name', $mother_name ?: '—'],
                    ]],
                ] as [$section, $rows])
                <div class="rounded-xl border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $section }}</span>
                        <button wire:click="goToStep({{ $loop->index + 1 }})"
                                class="text-xs font-semibold hover:underline" style="color:#F97316;">Edit</button>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($rows as [$label, $value])
                        <div class="flex items-center justify-between px-4 py-2.5">
                            <span class="text-xs text-gray-400">{{ $label }}</span>
                            <span class="text-xs font-semibold text-gray-800">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- Declaration --}}
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-800 leading-relaxed">
                    By submitting this application, I confirm that all information provided is accurate and complete. I understand that providing false information may result in cancellation of my application.
                </div>
            </div>
            @endif

            {{-- ── Navigation Buttons ────────────────────────────────── --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-gray-50">
                <div>
                    @if($currentStep > 1)
                    <button wire:click="prevStep"
                            class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-white transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                        Back
                    </button>
                    @else
                    <a href="/" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                        ← Home
                    </a>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400">Step {{ $currentStep }} of {{ $totalSteps }}</span>
                    @if($currentStep < $totalSteps)
                    <button wire:click="nextStep"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-5 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-opacity disabled:opacity-60"
                            style="background-color:#F97316;">
                        <span wire:loading.remove>Continue</span>
                        <span wire:loading>Please wait...</span>
                        <svg wire:loading.remove class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                    @else
                    <button wire:click="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-1.5 px-6 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-opacity disabled:opacity-60"
                            style="background-color:#1E3A5F;">
                        <span wire:loading.remove>Submit Application</span>
                        <span wire:loading>Submitting...</span>
                    </button>
                    @endif
                </div>
            </div>

        </div>{{-- end card --}}

        {{-- Help text --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            Need help? Call us at
            <a href="tel:+919940528779" class="text-orange-500 font-semibold">+91 99405 28779</a>
            or WhatsApp us.
        </p>

    </div>
</div>
