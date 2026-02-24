<div>
    {{-- Success Banner --}}
    @if($uploadSuccess)
        <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4"
             x-data x-init="setTimeout(() => $el.remove(), 5000)">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-green-800">Submitted successfully!</p>
                <p class="text-xs text-green-600 mt-0.5">Your answer sheet has been submitted and will be reviewed by your faculty.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ── Upload Form ───────────────────────────────────────────── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Submit Answer Sheet</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload your IAS answer for faculty evaluation.</p>
            </div>

            <form wire:submit.prevent="upload" class="p-6 space-y-5">

                {{-- Subject --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="subject_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                        <option value="">Select a subject...</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Question --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="question_text" rows="4"
                              placeholder="Type or paste the question you are answering..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent resize-none placeholder-gray-400"></textarea>
                    @error('question_text') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Answer Sheet (PDF or Image) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-200 rounded-xl px-6 py-8 hover:border-[#1e3a5f] transition-colors cursor-pointer"
                         x-data="{ dragging: false }"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="dragging = false">
                        <input type="file" wire:model="answer_file"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="flex flex-col items-center text-center">
                            <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            @if($answer_file)
                                <p class="text-sm font-semibold text-[#1e3a5f]">{{ $answer_file->getClientOriginalName() }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ round($answer_file->getSize() / 1024) }} KB</p>
                            @else
                                <p class="text-sm font-medium text-gray-600">Drop file here or <span class="text-[#1e3a5f] underline">browse</span></p>
                                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG — max 10 MB</p>
                            @endif
                        </div>
                    </div>
                    @error('answer_file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    {{-- Upload progress --}}
                    <div wire:loading wire:target="answer_file" class="mt-2">
                        <div class="h-1.5 bg-blue-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#1e3a5f] animate-pulse rounded-full" style="width: 60%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Uploading...</p>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#1e3a5f] hover:bg-blue-900 text-white font-semibold text-sm py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="upload">Submit Answer Sheet</span>
                    <span wire:loading wire:target="upload" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Submitting...
                    </span>
                </button>
            </form>
        </div>

        {{-- ── My Submissions ────────────────────────────────────────── --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">My Submissions</h2>
            </div>

            @if(empty($mySubmissions))
                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <p class="text-sm">No submissions yet.</p>
                    <p class="text-xs mt-1 text-gray-300">Submit your first answer sheet using the form.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="text-left px-5 py-3">Subject</th>
                                <th class="text-left px-5 py-3">Submitted</th>
                                <th class="text-left px-5 py-3">Status</th>
                                <th class="text-left px-5 py-3">Score</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($mySubmissions as $sub)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-gray-900">
                                        {{ $sub['subject']['name'] ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($sub['submitted_at'])->format('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @php
                                            $statusMap = [
                                                'pending'   => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-700'],
                                                'evaluated' => ['label' => 'Evaluated', 'class' => 'bg-green-100 text-green-700'],
                                                'rejected'  => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-700'],
                                            ];
                                            $s = $statusMap[$sub['status']] ?? ['label' => ucfirst($sub['status']), 'class' => 'bg-gray-100 text-gray-600'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $s['class'] }}">
                                            {{ $s['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if(isset($sub['evaluation']['total_score']))
                                            <span class="font-bold
                                                {{ $sub['evaluation']['total_score'] >= 75 ? 'text-green-600' : ($sub['evaluation']['total_score'] >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                                {{ $sub['evaluation']['total_score'] }}/100
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        @if(isset($sub['evaluation']))
                                            <a href="{{ route('student.ias.feedback', $sub['id']) }}"
                                               class="text-xs font-medium text-[#1e3a5f] hover:text-orange-500 transition-colors">
                                                View Feedback &rarr;
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
