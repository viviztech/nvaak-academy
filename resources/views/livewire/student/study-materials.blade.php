<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Study Materials</h2>
        <p class="text-sm text-gray-500 mt-0.5">Access your course materials, notes, and resources</p>
    </div>

    <!-- Subject Tabs (horizontal scroll) -->
    <div class="flex gap-2 overflow-x-auto pb-2 mb-4">
        <button wire:click="selectSubject('')"
                class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors
                {{ !$selectedSubjectId ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                @style(["background-color: #f97316" => !$selectedSubjectId])>
            All Subjects
        </button>
        @foreach($this->subjects as $subject)
            <button wire:click="selectSubject('{{ $subject->id }}')"
                    class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors
                    {{ $selectedSubjectId == $subject->id ? 'text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                    @style(["background-color: #1e3a5f" => $selectedSubjectId == $subject->id])>
                {{ $subject->name }}
            </button>
        @endforeach
    </div>

    <!-- Filters Row -->
    <div class="flex flex-wrap gap-3 mb-6">
        <!-- Chapter filter (when subject selected) -->
        @if($selectedSubjectId && $this->chapters->count())
            <select wire:model.live="selectedChapterId" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none">
                <option value="">All Chapters</option>
                @foreach($this->chapters as $chapter)
                    <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                @endforeach
            </select>
        @endif

        <select wire:model.live="typeFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none">
            <option value="">All Types</option>
            <option value="pdf">PDF</option>
            <option value="video">Video</option>
            <option value="audio">Audio</option>
            <option value="link">Link</option>
            <option value="doc">Document</option>
            <option value="ppt">Presentation</option>
        </select>

        <div class="flex-1 min-w-48">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search materials..."
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-300">
        </div>
    </div>

    <!-- Materials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($this->materials as $material)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-shadow">
                <!-- Type indicator -->
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                        @if($material->material_type === 'pdf') bg-red-100
                        @elseif($material->material_type === 'video') bg-purple-100
                        @elseif($material->material_type === 'audio') bg-blue-100
                        @elseif($material->material_type === 'link') bg-green-100
                        @else bg-indigo-100 @endif">
                        @if($material->material_type === 'pdf')
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/></svg>
                        @elseif($material->material_type === 'video')
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                        @elseif($material->material_type === 'audio')
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        @elseif($material->material_type === 'link')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        @else
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-800 leading-snug">{{ $material->title }}</h4>
                        <p class="text-xs text-gray-500 truncate">{{ $material->subject?->name }}
                            @if($material->chapter) &rsaquo; {{ $material->chapter->name }} @endif
                        </p>
                    </div>
                </div>

                @if($material->description)
                    <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $material->description }}</p>
                @endif

                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium uppercase
                        @if($material->material_type === 'pdf') bg-red-100 text-red-700
                        @elseif($material->material_type === 'video') bg-purple-100 text-purple-700
                        @elseif($material->material_type === 'audio') bg-blue-100 text-blue-700
                        @elseif($material->material_type === 'link') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $material->material_type }}
                    </span>
                    @if($material->file_size_mb)
                        <span class="text-xs text-gray-400">{{ $material->file_size_mb }} MB</span>
                    @endif
                    @if($material->duration_minutes)
                        <span class="text-xs text-gray-400">{{ $material->duration_minutes }} min</span>
                    @endif
                </div>

                <!-- Content Viewer / Action Button -->
                @if($material->material_type === 'pdf' && $material->file_path)
                    <div class="mt-2 border border-gray-200 rounded-lg overflow-hidden" style="height: 200px;">
                        <embed src="{{ Storage::url($material->file_path) }}" type="application/pdf" class="w-full h-full">
                    </div>
                    <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                       wire:click="logAccess({{ $material->id }})"
                       class="mt-2 flex items-center justify-center gap-2 w-full py-2 text-sm font-medium text-white rounded-lg transition-colors"
                       style="background-color: #1e3a5f;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download PDF
                    </a>
                @elseif($material->material_type === 'video')
                    @php
                        $videoId = null;
                        if ($material->external_url && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $material->external_url, $m)) {
                            $videoId = $m[1];
                        }
                    @endphp
                    @if($videoId)
                        <div class="mt-2 rounded-lg overflow-hidden" style="aspect-ratio: 16/9;">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full"
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                    @elseif($material->file_path)
                        <video controls class="w-full mt-2 rounded-lg" wire:click="logAccess({{ $material->id }})">
                            <source src="{{ Storage::url($material->file_path) }}">
                        </video>
                    @elseif($material->external_url)
                        <a href="{{ $material->external_url }}" target="_blank"
                           wire:click="logAccess({{ $material->id }})"
                           class="mt-2 flex items-center justify-center gap-2 w-full py-2 text-sm font-medium text-white rounded-lg transition-colors"
                           style="background-color: #7c3aed;">
                            Watch Video
                        </a>
                    @endif
                @elseif($material->material_type === 'link' && $material->external_url)
                    <a href="{{ $material->external_url }}" target="_blank"
                       wire:click="logAccess({{ $material->id }})"
                       class="mt-2 flex items-center justify-center gap-2 w-full py-2 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors border border-green-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Open External Link
                    </a>
                @elseif($material->file_path)
                    <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                       wire:click="logAccess({{ $material->id }})"
                       class="mt-2 flex items-center justify-center gap-2 w-full py-2 text-sm font-medium text-white rounded-lg transition-colors"
                       style="background-color: #1e3a5f;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download
                    </a>
                @endif
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 text-sm">No materials found for your selection.</p>
            </div>
        @endforelse
    </div>
</div>
