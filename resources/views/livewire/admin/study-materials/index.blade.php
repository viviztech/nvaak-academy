<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Study Materials</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage PDFs, videos, and learning resources</p>
        </div>
        <button wire:click="openModal"
                class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors"
                style="background-color: #f97316;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Upload Material
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-gray-800">{{ $totalCount }}</p>
            <p class="text-sm text-gray-500">Total Materials</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-green-600">{{ $publishedCount }}</p>
            <p class="text-sm text-gray-500">Published</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-red-500">{{ $pdfCount }}</p>
            <p class="text-sm text-gray-500">PDF Documents</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
            <p class="text-2xl font-bold text-purple-600">{{ $videoCount }}</p>
            <p class="text-sm text-gray-500">Video Lessons</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-48">
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search materials..."
                       class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <select wire:model.live="subjectFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                <option value="">All Subjects</option>
                @foreach($this->subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="typeFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                <option value="">All Types</option>
                <option value="pdf">PDF</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="link">Link</option>
                <option value="doc">Document</option>
                <option value="ppt">PPT</option>
            </select>
            <select wire:model.live="batchFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                <option value="">All Batches</option>
                @foreach($this->batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Material Cards Grid -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        @forelse($this->materials as $material)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-3 mb-3">
                    <!-- Type Icon -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                        @if($material->material_type === 'pdf') bg-red-100
                        @elseif($material->material_type === 'video') bg-purple-100
                        @elseif($material->material_type === 'audio') bg-blue-100
                        @elseif($material->material_type === 'link') bg-green-100
                        @elseif($material->material_type === 'doc') bg-indigo-100
                        @else bg-orange-100 @endif">
                        @if($material->material_type === 'pdf')
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/></svg>
                        @elseif($material->material_type === 'video')
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg>
                        @elseif($material->material_type === 'link')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        @else
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $material->title }}</h4>
                        <p class="text-xs text-gray-500 truncate">{{ $material->subject?->name }}</p>
                        @if($material->chapter)
                            <p class="text-xs text-gray-400 truncate">{{ $material->chapter?->name }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium uppercase
                        @if($material->material_type === 'pdf') bg-red-100 text-red-700
                        @elseif($material->material_type === 'video') bg-purple-100 text-purple-700
                        @elseif($material->material_type === 'audio') bg-blue-100 text-blue-700
                        @elseif($material->material_type === 'link') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $material->material_type }}
                    </span>
                    @if($material->batch)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700">
                            {{ $material->batch->name }}
                        </span>
                    @endif
                    @if($material->file_size_mb)
                        <span class="text-xs text-gray-400">{{ $material->file_size_mb }} MB</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <span>{{ number_format($material->view_count) }} views</span>
                    <span>&bull;</span>
                    <span>{{ number_format($material->download_count) }} downloads</span>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <button wire:click="togglePublished({{ $material->id }})"
                            class="text-xs px-2 py-1 rounded-md font-medium transition-colors
                            {{ $material->is_published ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ $material->is_published ? 'Published' : 'Draft' }}
                    </button>
                    <div class="flex items-center gap-1">
                        @if($material->file_path)
                            <a href="{{ Storage::url($material->file_path) }}" target="_blank"
                               class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        @elseif($material->external_url)
                            <a href="{{ $material->external_url }}" target="_blank"
                               class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                        <button wire:click="delete({{ $material->id }})"
                                wire:confirm="Delete this material?"
                                class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 text-sm">No study materials found.</p>
                <button wire:click="openModal" class="mt-3 text-sm text-orange-500 hover:text-orange-700 font-medium">Upload your first material</button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    {{ $this->materials->links() }}

    <!-- Upload Modal -->
    @if($showUploadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" wire:click="resetModal"></div>

                <div class="relative inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Upload Study Material</h3>
                        <button wire:click="resetModal" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="px-6 py-4 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input wire:model="title" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" placeholder="e.g. Cell Division - Mitosis Notes">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300" placeholder="Brief description of this material..."></textarea>
                        </div>

                        <!-- Material Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Material Type <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-6 gap-2">
                                @foreach(['pdf' => ['label' => 'PDF', 'color' => 'red'], 'video' => ['label' => 'Video', 'color' => 'purple'], 'audio' => ['label' => 'Audio', 'color' => 'blue'], 'link' => ['label' => 'Link', 'color' => 'green'], 'doc' => ['label' => 'Doc', 'color' => 'indigo'], 'ppt' => ['label' => 'PPT', 'color' => 'orange']] as $type => $meta)
                                    <label class="cursor-pointer">
                                        <input wire:model="material_type" type="radio" value="{{ $type }}" class="sr-only">
                                        <div class="text-center p-2 rounded-lg border-2 transition-colors text-xs font-medium
                                            {{ $material_type === $type ? 'border-orange-400 bg-orange-50 text-orange-700' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                            {{ $meta['label'] }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subject + Chapter + Topic -->
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                                <select wire:model.live="subject_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                                    <option value="">Select Subject</option>
                                    @foreach($this->subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('subject_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Chapter</label>
                                <select wire:model.live="chapter_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none" @if(!$subject_id) disabled @endif>
                                    <option value="">Select Chapter</option>
                                    @foreach($this->chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                                <select wire:model="topic_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none" @if(!$chapter_id) disabled @endif>
                                    <option value="">Select Topic</option>
                                    @foreach($this->topics as $topic)
                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Batch + Access Type -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Batch</label>
                                <select wire:model="batch_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                                    <option value="">All Batches</option>
                                    @foreach($this->batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Access Type</label>
                                <select wire:model="access_type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none">
                                    <option value="batch">Batch Only</option>
                                    <option value="course">Course</option>
                                    <option value="all">All Students</option>
                                </select>
                            </div>
                        </div>

                        <!-- File Upload or URL -->
                        @if(in_array($material_type, ['pdf', 'doc', 'ppt', 'audio']))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-orange-400 transition-colors">
                                    <input wire:model="file" type="file" class="hidden" id="file-upload"
                                           accept="{{ $material_type === 'pdf' ? '.pdf' : ($material_type === 'audio' ? 'audio/*' : '.doc,.docx,.ppt,.pptx') }}">
                                    <label for="file-upload" class="cursor-pointer">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-sm text-gray-500">Click to upload or drag and drop</p>
                                        <p class="text-xs text-gray-400 mt-1">Max 50MB</p>
                                    </label>
                                </div>
                                @if($file)
                                    <p class="text-xs text-green-600 mt-1">File selected: {{ $file->getClientOriginalName() }}</p>
                                @endif
                                @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">External URL</label>
                                <input wire:model="external_url" type="url" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                                       placeholder="{{ $material_type === 'video' ? 'https://www.youtube.com/watch?v=...' : 'https://...' }}">
                                @error('external_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <!-- Tags + Free Preview -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tags (comma separated)</label>
                                <input wire:model="tags_input" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none" placeholder="NEET, Biology, Cell">
                            </div>
                            <div class="flex items-center gap-2 pt-6">
                                <input wire:model="is_free_preview" type="checkbox" id="free-preview" class="rounded text-orange-500">
                                <label for="free-preview" class="text-sm text-gray-700">Free Preview (accessible without enrollment)</label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                            <button type="button" wire:click="resetModal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
                                    style="background-color: #f97316;"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>Upload Material</span>
                                <span wire:loading>Uploading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
