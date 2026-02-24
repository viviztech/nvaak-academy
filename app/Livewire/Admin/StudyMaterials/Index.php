<?php

namespace App\Livewire\Admin\StudyMaterials;

use App\Models\Batch;
use App\Models\Chapter;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public string $search        = '';
    public string $subjectFilter = '';
    public string $chapterFilter = '';
    public string $typeFilter    = '';
    public string $batchFilter   = '';
    public int $perPage          = 20;

    // Modal
    public bool $showUploadModal  = false;
    public string $title          = '';
    public string $description    = '';
    public string $material_type  = 'pdf';
    public string $subject_id     = '';
    public string $chapter_id     = '';
    public string $topic_id       = '';
    public string $batch_id       = '';
    public string $access_type    = 'batch';
    public bool $is_free_preview  = false;
    public string $external_url   = '';
    public string $tags_input     = '';
    public $file;

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'material_type' => 'required|in:pdf,video,audio,link,doc,ppt',
            'subject_id'   => 'required|exists:subjects,id',
            'chapter_id'   => 'nullable|exists:chapters,id',
            'topic_id'     => 'nullable|exists:topics,id',
            'batch_id'     => 'nullable|exists:batches,id',
            'access_type'  => 'required|in:batch,course,all',
            'external_url' => 'nullable|url',
            'file'         => 'nullable|file|max:51200',
            'tags_input'   => 'nullable|string',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter(): void
    {
        $this->chapterFilter = '';
        $this->resetPage();
    }

    public function getSubjectsProperty()
    {
        return Subject::active()->orderBy('name')->get();
    }

    public function getChaptersProperty()
    {
        $sid = $this->subjectFilter ?: $this->subject_id;
        if (! $sid) {
            return collect();
        }

        return Chapter::where('subject_id', $sid)->active()->ordered()->get();
    }

    public function getTopicsProperty()
    {
        $cid = $this->chapterFilter ?: $this->chapter_id;
        if (! $cid) {
            return collect();
        }

        return Topic::where('chapter_id', $cid)->get();
    }

    public function getBatchesProperty()
    {
        return Batch::active()->orderBy('name')->get();
    }

    public function getMaterialsProperty()
    {
        return StudyMaterial::with(['subject', 'chapter', 'batch', 'createdBy'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->subjectFilter, fn ($q) => $q->where('subject_id', $this->subjectFilter))
            ->when($this->chapterFilter, fn ($q) => $q->where('chapter_id', $this->chapterFilter))
            ->when($this->typeFilter, fn ($q) => $q->where('material_type', $this->typeFilter))
            ->when($this->batchFilter, fn ($q) => $q->where('batch_id', $this->batchFilter))
            ->latest()
            ->paginate($this->perPage);
    }

    public function save(): void
    {
        $this->validate();

        $filePath  = null;
        $sizeKb    = null;

        if ($this->file) {
            $stored   = $this->file->store('materials', 'public');
            $filePath = $stored;
            $sizeKb   = (int) round($this->file->getSize() / 1024);
        }

        $tags = [];
        if ($this->tags_input) {
            $tags = array_map('trim', explode(',', $this->tags_input));
        }

        StudyMaterial::create([
            'institute_id'   => Auth::user()->institute_id ?? 1,
            'batch_id'       => $this->batch_id ?: null,
            'subject_id'     => $this->subject_id,
            'chapter_id'     => $this->chapter_id ?: null,
            'topic_id'       => $this->topic_id ?: null,
            'title'          => $this->title,
            'description'    => $this->description,
            'material_type'  => $this->material_type,
            'file_path'      => $filePath,
            'external_url'   => $this->external_url ?: null,
            'file_size_kb'   => $sizeKb,
            'is_free_preview' => $this->is_free_preview,
            'access_type'    => $this->access_type,
            'tags'           => $tags,
            'is_published'   => false,
            'created_by'     => Auth::id(),
        ]);

        $this->resetModal();
        session()->flash('success', 'Study material uploaded successfully.');
    }

    public function delete(int $id): void
    {
        $material = StudyMaterial::findOrFail($id);
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        session()->flash('success', 'Material deleted.');
    }

    public function togglePublished(int $id): void
    {
        $material              = StudyMaterial::findOrFail($id);
        $material->is_published = ! $material->is_published;
        if ($material->is_published && ! $material->published_at) {
            $material->published_at = now();
        }
        $material->save();
    }

    public function openModal(): void
    {
        $this->resetModal();
        $this->showUploadModal = true;
    }

    public function resetModal(): void
    {
        $this->reset([
            'showUploadModal', 'title', 'description', 'material_type',
            'subject_id', 'chapter_id', 'topic_id', 'batch_id',
            'access_type', 'is_free_preview', 'external_url', 'file', 'tags_input',
        ]);
        $this->material_type = 'pdf';
        $this->access_type   = 'batch';
    }

    public function render()
    {
        $totalCount     = StudyMaterial::count();
        $publishedCount = StudyMaterial::where('is_published', true)->count();
        $pdfCount       = StudyMaterial::where('material_type', 'pdf')->count();
        $videoCount     = StudyMaterial::where('material_type', 'video')->count();

        return view('livewire.admin.study-materials.index', [
            'totalCount'     => $totalCount,
            'publishedCount' => $publishedCount,
            'pdfCount'       => $pdfCount,
            'videoCount'     => $videoCount,
        ])->layout('layouts.admin', ['title' => 'Study Materials']);
    }
}
