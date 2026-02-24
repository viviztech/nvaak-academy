<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">NEET Rank Predictor</h2>
        <p class="text-sm text-gray-500 mt-0.5">Predict student NEET rank based on performance data</p>
    </div>

    <!-- Student + Batch Selectors -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Student</label>
                <select wire:model.live="studentId" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="">Choose a student...</option>
                    @foreach($this->students as $student)
                        <option value="{{ $student->id }}">{{ $student->user?->name }} ({{ $student->student_code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Batch</label>
                <select wire:model.live="batchId" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="">Choose a batch...</option>
                    @foreach($this->batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if($studentId && $batchId)
        @if($this->latestSnapshot)
            <!-- Performance Overview -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500 mb-1">Overall Score</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $this->latestSnapshot->overall_score_percent }}%</p>
                    <div class="mt-2 w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $this->latestSnapshot->overall_score_percent >= 70 ? 'bg-green-500' : ($this->latestSnapshot->overall_score_percent >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width: {{ $this->latestSnapshot->overall_score_percent }}%"></div>
                    </div>
                    @if($this->latestSnapshot->improvement_from_last !== null)
                        <p class="text-xs mt-2 {{ $this->latestSnapshot->improvement_from_last >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $this->latestSnapshot->improvement_from_last >= 0 ? '&#8593;' : '&#8595;' }}
                            {{ abs($this->latestSnapshot->improvement_from_last) }}% from last snapshot
                        </p>
                    @endif
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500 mb-1">Batch Rank</p>
                    <p class="text-3xl font-bold" style="color: #1e3a5f;">
                        #{{ $this->latestSnapshot->rank_in_batch ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Based on {{ $this->latestSnapshot->exams_attempted }} exams</p>
                    <p class="text-xs text-gray-400">Snapshot: {{ $this->latestSnapshot->snapshot_date?->format('d M Y') }}</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <p class="text-sm text-gray-500 mb-1">Attendance</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $this->latestSnapshot->attendance_percent }}%</p>
                    <div class="mt-2 w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $this->latestSnapshot->attendance_percent >= 75 ? 'bg-green-500' : 'bg-red-500' }}"
                             style="width: {{ min($this->latestSnapshot->attendance_percent, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- NEET Prediction Card -->
            <div class="bg-gradient-to-r from-blue-900 to-indigo-800 rounded-xl p-6 text-white mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm mb-1">Predicted NEET Score</p>
                        <p class="text-5xl font-bold">{{ $this->prediction['score'] ?? 'N/A' }}</p>
                        <p class="text-blue-200 text-sm mt-1">out of 720 marks</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-200 text-sm mb-1">Predicted Rank Range</p>
                        <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-xl backdrop-blur">
                            <span class="text-lg font-bold">{{ $this->prediction['rank']['label'] ?? 'N/A' }}</span>
                        </div>
                        <p class="text-blue-300 text-xs mt-2">Based on NEET 2024 rank bands</p>
                    </div>
                </div>
            </div>

            <!-- Subject-wise Scores -->
            @if($this->latestSnapshot->subject_scores && count($this->latestSnapshot->subject_scores))
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Subject-wise Performance</h3>
                    <div class="space-y-4">
                        @foreach($this->latestSnapshot->subject_scores as $score)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $score['name'] }}</span>
                                    <span class="text-sm font-bold {{ ($score['percentage'] ?? 0) >= 70 ? 'text-green-600' : (($score['percentage'] ?? 0) >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $score['percentage'] ?? 0 }}%
                                    </span>
                                </div>
                                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500
                                        {{ ($score['percentage'] ?? 0) >= 70 ? 'bg-green-500' : (($score['percentage'] ?? 0) >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                         style="width: {{ $score['percentage'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @else
            <!-- No snapshot -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-gray-600 font-medium">No performance snapshot available</p>
                <p class="text-gray-400 text-sm mt-1">Run <code class="bg-gray-100 px-1 rounded">php artisan nvaak:snapshots</code> to generate snapshots.</p>
            </div>
        @endif
    @else
        <!-- Instruction state -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background-color: #f97316;">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-gray-700 font-semibold text-lg">Select a Student and Batch</p>
            <p class="text-gray-400 text-sm mt-1">Choose a student and batch above to view their NEET rank prediction.</p>
        </div>
    @endif
</div>
