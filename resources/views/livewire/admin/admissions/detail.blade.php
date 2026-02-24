<div>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-5 flex items-center gap-3 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            <svg class="h-4 w-4 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header bar ─────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.admissions.index') }}"
               class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-700 mb-2">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Admissions
            </a>
            <h1 class="text-xl font-extrabold text-gray-900">
                {{ trim(collect([$admission->first_name, $admission->middle_name, $admission->last_name])->filter()->implode(' ')) }}
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Application No: <span class="font-mono font-semibold text-gray-700">{{ $admission->application_number }}</span>
            </p>
        </div>

        {{-- Status badge + action buttons --}}
        <div class="flex items-center gap-3 flex-wrap shrink-0">
            @php
                $statusStyles = [
                    'draft'        => 'bg-gray-100 text-gray-600',
                    'submitted'    => 'bg-blue-50 text-blue-700',
                    'under_review' => 'bg-yellow-50 text-yellow-700',
                    'approved'     => 'bg-green-50 text-green-700',
                    'rejected'     => 'bg-red-50 text-red-600',
                    'admitted'     => 'bg-purple-50 text-purple-700',
                    'cancelled'    => 'bg-gray-100 text-gray-500',
                ];
                $dotStyles = [
                    'submitted'    => 'bg-blue-400 animate-pulse',
                    'under_review' => 'bg-yellow-400 animate-pulse',
                    'approved'     => 'bg-green-500',
                    'rejected'     => 'bg-red-500',
                    'admitted'     => 'bg-purple-500',
                ];
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusStyles[$admission->status] ?? 'bg-gray-100 text-gray-600' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $dotStyles[$admission->status] ?? 'bg-gray-400' }}"></span>
                {{ ucfirst(str_replace('_', ' ', $admission->status)) }}
            </span>

            @if(in_array($admission->status, ['submitted', 'under_review']))
                <button wire:click="approve"
                        wire:confirm="Approve this application?"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-lg bg-green-600 hover:bg-green-700 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Approve
                </button>
                <button wire:click="$set('showRejectModal', true)"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white rounded-lg bg-red-600 hover:bg-red-700 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reject
                </button>
            @endif
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ── Left: main details (2/3) ──────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Personal Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#1E3A5F;">1</div>
                    <h2 class="font-bold text-gray-800 text-sm">Personal Information</h2>
                </div>
                <div class="p-5">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                        @foreach([
                            ['Full Name',       trim(collect([$admission->first_name, $admission->middle_name, $admission->last_name])->filter()->implode(' '))],
                            ['Date of Birth',   $admission->date_of_birth?->format('d M Y') ?? '—'],
                            ['Gender',          ucfirst($admission->gender ?? '—')],
                            ['Blood Group',     $admission->blood_group ?? '—'],
                            ['Aadhaar Number',  $admission->aadhaar_number ?? '—'],
                            ['Nationality',     $admission->nationality ?? '—'],
                            ['Religion',        $admission->religion ?? '—'],
                            ['Caste Category',  strtoupper($admission->caste_category ?? '—')],
                        ] as [$label, $value])
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 mt-4 pt-4 grid sm:grid-cols-2 gap-x-6 gap-y-4">
                        @foreach([
                            ['Phone',            $admission->phone ?? '—'],
                            ['Alternate Phone',  $admission->alternate_phone ?? '—'],
                            ['Email',            $admission->email ?? '—'],
                            ['Current Address',  $admission->current_address ?? '—'],
                            ['Permanent Address',$admission->permanent_address ?? '—'],
                            ['City',             $admission->city ?? '—'],
                            ['State',            $admission->state ?? '—'],
                            ['Postal Code',      $admission->postal_code ?? '—'],
                        ] as [$label, $value])
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Academic Background --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#1E3A5F;">2</div>
                    <h2 class="font-bold text-gray-800 text-sm">Academic Background</h2>
                </div>
                <div class="p-5">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                        @foreach([
                            ['Course Applied',       strtoupper($admission->course_applied ?? '—')],
                            ['Previous Institution', $admission->previous_institution ?? '—'],
                            ['Board',                strtoupper($admission->board ?? '—')],
                            ['Percentage / CGPA',    $admission->previous_percentage ? $admission->previous_percentage.'%' : '—'],
                            ['Year of Passing',      $admission->year_of_passing ?? '—'],
                        ] as [$label, $value])
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>

                    @if($admission->neet_previous_score || $admission->neet_previous_rank)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-3">Previous NEET Attempt</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-extrabold text-blue-700">{{ $admission->neet_previous_score ?? '—' }}</p>
                                <p class="text-xs text-blue-500 mt-0.5">Score (out of 720)</p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-2xl font-extrabold text-blue-700">{{ $admission->neet_previous_rank ? '#'.$admission->neet_previous_rank : '—' }}</p>
                                <p class="text-xs text-blue-500 mt-0.5">All India Rank</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Guardian Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#1E3A5F;">3</div>
                    <h2 class="font-bold text-gray-800 text-sm">Guardian Information</h2>
                </div>
                <div class="p-5 space-y-5">

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-1 border-b border-gray-100">Father's Details</p>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3">
                            @foreach([
                                ['Name',          $admission->father_name ?? '—'],
                                ['Occupation',    $admission->father_occupation ?? '—'],
                                ['Phone',         $admission->father_phone ?? '—'],
                                ['Email',         $admission->father_email ?? '—'],
                                ['Annual Income', $admission->father_income ? '₹'.number_format($admission->father_income) : '—'],
                            ] as [$label, $value])
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $label }}</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    @if($admission->mother_name)
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-1 border-b border-gray-100">Mother's Details</p>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3">
                            @foreach([
                                ['Name',       $admission->mother_name],
                                ['Occupation', $admission->mother_occupation ?? '—'],
                                ['Phone',      $admission->mother_phone ?? '—'],
                                ['Email',      $admission->mother_email ?? '—'],
                            ] as [$label, $value])
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $label }}</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($admission->guardian_name)
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-1 border-b border-gray-100">Guardian's Details</p>
                        <div class="grid sm:grid-cols-3 gap-x-6 gap-y-3">
                            @foreach([
                                ['Name',     $admission->guardian_name],
                                ['Relation', ucfirst($admission->guardian_relation ?? '—')],
                                ['Phone',    $admission->guardian_phone ?? '—'],
                            ] as [$label, $value])
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-0.5">{{ $label }}</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Documents --}}
            @if($admission->documents && count($admission->documents) > 0)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="h-7 w-7 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0" style="background:#1E3A5F;">4</div>
                    <h2 class="font-bold text-gray-800 text-sm">Uploaded Documents</h2>
                </div>
                <div class="p-5">
                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach($admission->documents as $type => $path)
                        <a href="{{ Storage::url($path) }}" target="_blank"
                           class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center shrink-0 bg-gray-100 group-hover:bg-blue-100">
                                @if(str_ends_with(strtolower($path), '.pdf'))
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                @else
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $type) }}</p>
                                <p class="text-xs text-blue-500 group-hover:underline mt-0.5">View / Download</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- ── Right sidebar (1/3) ─────────────────────────────────── --}}
        <div class="space-y-5">

            {{-- Application Meta --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Application Info</h2>
                </div>
                <div class="p-5 space-y-3">
                    @foreach([
                        ['Submitted',   $admission->submitted_at?->format('d M Y, h:i A') ?? '—'],
                        ['Reviewed At', $admission->reviewed_at?->format('d M Y, h:i A') ?? '—'],
                        ['Reviewed By', $admission->reviewedBy?->name ?? '—'],
                        ['Approved At', $admission->approved_at?->format('d M Y, h:i A') ?? '—'],
                        ['Source',      ucfirst($admission->source ?? 'Online')],
                        ['Institute',   $admission->institute?->name ?? '—'],
                    ] as [$label, $value])
                    <div class="flex justify-between gap-2">
                        <span class="text-xs text-gray-400 font-medium shrink-0">{{ $label }}</span>
                        <span class="text-xs font-semibold text-gray-700 text-right">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Batch Assignment --}}
            @if(in_array($admission->status, ['submitted', 'under_review', 'approved']))
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Assign Batch</h2>
                </div>
                <div class="p-5">
                    <select wire:model="selectedBatchId"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 mb-1">
                        <option value="">No batch assigned</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400">
                        Current: <span class="font-medium text-gray-600">{{ $admission->batch?->name ?? 'None' }}</span>
                    </p>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Batch</h2>
                </div>
                <div class="p-5">
                    <p class="text-sm font-semibold text-gray-700">{{ $admission->batch?->name ?? '—' }}</p>
                </div>
            </div>
            @endif

            {{-- Rejection Reason --}}
            @if($admission->status === 'rejected' && $admission->rejection_reason)
            <div class="bg-red-50 rounded-xl border border-red-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-red-100">
                    <h2 class="font-bold text-red-700 text-sm">Rejection Reason</h2>
                </div>
                <div class="p-5">
                    <p class="text-sm text-red-600 leading-relaxed">{{ $admission->rejection_reason }}</p>
                </div>
            </div>
            @endif

            {{-- Admin Remarks --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Admin Remarks</h2>
                </div>
                <div class="p-5">
                    <textarea wire:model="adminRemarks" rows="4"
                              placeholder="Add internal notes or remarks..."
                              class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
                    <button wire:click="saveRemarks"
                            class="mt-2 w-full py-2 text-xs font-bold text-white rounded-lg transition-opacity hover:opacity-90"
                            style="background-color:#1E3A5F;">
                        <span wire:loading.remove wire:target="saveRemarks">Save Remarks</span>
                        <span wire:loading wire:target="saveRemarks">Saving…</span>
                    </button>
                </div>
            </div>

            {{-- Quick Contact --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Quick Contact</h2>
                </div>
                <div class="p-5 space-y-2">
                    @if($admission->phone)
                    <a href="tel:{{ $admission->phone }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-sm font-medium text-gray-700">{{ $admission->phone }}</span>
                    </a>
                    <a href="https://wa.me/91{{ preg_replace('/\D/', '', $admission->phone) }}" target="_blank"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-green-50 transition-colors">
                        <svg class="h-4 w-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span class="text-sm font-medium text-gray-700">WhatsApp</span>
                    </a>
                    @endif
                    @if($admission->email)
                    <a href="mailto:{{ $admission->email }}"
                       class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-blue-50 transition-colors">
                        <svg class="h-4 w-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm font-medium text-gray-700 truncate">{{ $admission->email }}</span>
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ── Reject Modal ────────────────────────────────────────────────── --}}
    @if($showRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-base font-bold text-gray-900 mb-1">Reject Application</h3>
            <p class="text-xs text-gray-500 mb-4">Provide a clear reason. This is stored internally for admin records.</p>

            <textarea wire:model="rejectionReason" rows="4"
                      placeholder="e.g. Incomplete documents, does not meet eligibility criteria..."
                      class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-red-300 mb-1"></textarea>
            @error('rejectionReason') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror

            <div class="flex gap-3 mt-4">
                <button wire:click="$set('showRejectModal', false)"
                        class="flex-1 py-2.5 text-sm font-semibold border border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="reject"
                        class="flex-1 py-2.5 text-sm font-bold text-white rounded-xl bg-red-600 hover:bg-red-700 transition-colors">
                    <span wire:loading.remove wire:target="reject">Confirm Reject</span>
                    <span wire:loading wire:target="reject">Rejecting…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
