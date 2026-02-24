<div x-data="{ activeTab: 'students' }">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Back + Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.batches.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $batch->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">Batch Detail & Management</p>
        </div>
    </div>

    {{-- Batch Info Card --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Code</p>
                <p class="text-sm font-mono font-bold text-indigo-700 mt-1">{{ $batch->code }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Course</p>
                <span class="inline-flex mt-1 px-2 py-0.5 rounded text-xs font-semibold
                    {{ $batch->course_type === 'neet' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ strtoupper($batch->course_type) }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Type</p>
                <p class="text-sm text-gray-700 mt-1 capitalize">{{ str_replace('_', ' ', $batch->batch_type) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Academic Year</p>
                <p class="text-sm text-gray-700 mt-1">{{ $batch->academic_year }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Medium</p>
                <p class="text-sm text-gray-700 mt-1 capitalize">{{ $batch->medium }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Classroom</p>
                <p class="text-sm text-gray-700 mt-1">{{ $batch->class_room ?: 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Start Date</p>
                <p class="text-sm text-gray-700 mt-1">{{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">End Date</p>
                <p class="text-sm text-gray-700 mt-1">{{ $batch->end_date ? \Carbon\Carbon::parse($batch->end_date)->format('d M Y') : 'N/A' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Strength</p>
                @php
                    $count = $batch->students()->count();
                    $fill = $batch->max_strength > 0 ? min(100, round(($count / $batch->max_strength) * 100)) : 0;
                    $barColor = $fill >= 90 ? 'bg-red-500' : ($fill >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                @endphp
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $fill }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-600">{{ $count }}/{{ $batch->max_strength }}</span>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Status</p>
                <span class="inline-flex mt-1 px-2 py-0.5 rounded text-xs font-semibold
                    {{ $batch->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $batch->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        @if($batch->description)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Description</p>
                <p class="text-sm text-gray-600">{{ $batch->description }}</p>
            </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button @click="activeTab = 'students'"
                        :class="activeTab === 'students' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    Students
                    <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">{{ $this->students->count() }}</span>
                </button>
                <button @click="activeTab = 'faculty'"
                        :class="activeTab === 'faculty' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-6 py-4 text-sm font-medium border-b-2 transition-colors">
                    Faculty
                    <span class="ml-2 px-1.5 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">{{ $this->facultyList->count() }}</span>
                </button>
            </nav>
        </div>

        {{-- Students Tab --}}
        <div x-show="activeTab === 'students'" x-transition>
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <p class="text-sm text-gray-600">Enrolled students in this batch</p>
                <button wire:click="openAddStudentModal"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Student
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Roll No.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Enrolled</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($this->students as $index => $batchStudent)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-xs font-semibold text-indigo-700">
                                                {{ strtoupper(substr($batchStudent->student->user->name ?? 'S', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $batchStudent->student->user->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-gray-400">{{ $batchStudent->student->student_code ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $batchStudent->roll_number ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $batchStudent->enrolled_at ? \Carbon\Carbon::parse($batchStudent->enrolled_at)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium
                                        {{ $batchStudent->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $batchStudent->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.students.profile', $batchStudent->student->id) }}"
                                           class="text-xs text-indigo-600 hover:text-indigo-800">Profile</a>
                                        <button wire:click="removeStudent({{ $batchStudent->id }})"
                                                wire:confirm="Remove this student from the batch?"
                                                class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    No students enrolled yet. Click "Add Student" to enroll students.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Faculty Tab --}}
        <div x-show="activeTab === 'faculty'" x-transition>
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <p class="text-sm text-gray-600">Faculty assigned to this batch</p>
                <button wire:click="openAddFacultyModal"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Faculty
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Faculty</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Assigned On</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($this->facultyList as $batchFaculty)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <span class="text-xs font-semibold text-emerald-700">
                                                {{ strtoupper(substr($batchFaculty->faculty->user->name ?? 'F', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $batchFaculty->faculty->user->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-gray-400">{{ $batchFaculty->faculty->employee_code ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $batchFaculty->subject->name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if($batchFaculty->is_primary)
                                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs font-medium">Primary</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">Secondary</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $batchFaculty->assigned_at ? \Carbon\Carbon::parse($batchFaculty->assigned_at)->format('d M Y') : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="removeFaculty({{ $batchFaculty->id }})"
                                            wire:confirm="Remove this faculty from the batch?"
                                            class="text-xs text-red-500 hover:text-red-700">Remove</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    No faculty assigned yet. Click "Add Faculty" to assign faculty.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Student Modal --}}
    @if($showAddStudentModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
             x-data x-on:keydown.escape.window="$wire.set('showAddStudentModal', false)">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Add Student to Batch</h3>
                    <button wire:click="$set('showAddStudentModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit="addStudent" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search & Select Student <span class="text-red-500">*</span></label>
                        <input wire:model.live.debounce.300ms="studentSearch" type="text" placeholder="Type student name..."
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none mb-2">
                        <div class="border border-gray-200 rounded-lg max-h-40 overflow-y-auto">
                            @forelse($this->availableStudents as $student)
                                <button type="button"
                                        wire:click="$set('student_id', {{ $student->id }})"
                                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-left hover:bg-indigo-50 transition-colors
                                               {{ $student_id == $student->id ? 'bg-indigo-50 border-l-2 border-indigo-500' : '' }}">
                                    <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-semibold text-gray-600">{{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">{{ $student->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $student->student_code }}</p>
                                    </div>
                                    @if($student_id == $student->id)
                                        <svg class="w-4 h-4 text-indigo-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                            @empty
                                <div class="px-3 py-4 text-center text-sm text-gray-400">No students available to add</div>
                            @endforelse
                        </div>
                        @error('student_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Roll Number</label>
                        <input wire:model="roll_number" type="text" placeholder="e.g. 001"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Enrolled Date</label>
                        <input wire:model="student_enrolled_at" type="date"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showAddStudentModal', false)"
                                class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            <span wire:loading.remove wire:target="addStudent">Add Student</span>
                            <span wire:loading wire:target="addStudent">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Add Faculty Modal --}}
    @if($showAddFacultyModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
             x-data x-on:keydown.escape.window="$wire.set('showAddFacultyModal', false)">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Assign Faculty to Batch</h3>
                    <button wire:click="$set('showAddFacultyModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form wire:submit="addFaculty" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Faculty <span class="text-red-500">*</span></label>
                        <select wire:model="faculty_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('faculty_id') border-red-500 @enderror">
                            <option value="">Select Faculty</option>
                            @foreach($this->availableFaculty as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->user->name }} ({{ $faculty->employee_code }})</option>
                            @endforeach
                        </select>
                        @error('faculty_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                        <select wire:model="subject_id"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('subject_id') border-red-500 @enderror">
                            <option value="">Select Subject</option>
                            @foreach($this->subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input wire:model="is_primary" type="checkbox" id="is_primary_check"
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                        <label for="is_primary_check" class="text-sm text-gray-700">Set as Primary Faculty for this subject</label>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showAddFacultyModal', false)"
                                class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                        <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            <span wire:loading.remove wire:target="addFaculty">Assign Faculty</span>
                            <span wire:loading wire:target="addFaculty">Assigning...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
