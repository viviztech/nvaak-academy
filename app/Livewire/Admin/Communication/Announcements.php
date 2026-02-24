<?php

namespace App\Livewire\Admin\Communication;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Announcement;
use App\Models\Batch;

class Announcements extends Component
{
    use WithPagination;

    // List filters
    public string $search = '';

    // Create/Edit modal
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $body = '';
    public string $targetAudience = 'all';
    public array $targetBatchIds = [];
    public bool $isPinned = false;
    public string $publishedAt = '';
    public string $expiresAt = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->publishedAt = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $ann = Announcement::findOrFail($id);
        $this->editingId      = $id;
        $this->title          = $ann->title;
        $this->body           = $ann->body;
        $this->targetAudience = $ann->target_audience ?? 'all';
        $this->targetBatchIds = $ann->target_batch_ids ?? [];
        $this->isPinned       = $ann->is_pinned;
        $this->publishedAt    = $ann->published_at?->format('Y-m-d\TH:i') ?? '';
        $this->expiresAt      = $ann->expires_at?->format('Y-m-d\TH:i') ?? '';
        $this->showModal      = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'targetAudience' => 'required|in:all,students,faculty,batch',
        ]);

        Announcement::updateOrCreate(
            ['id' => $this->editingId],
            [
                'institute_id'     => auth()->user()->institute_id,
                'title'            => $this->title,
                'body'             => $this->body,
                'target_audience'  => $this->targetAudience,
                'target_batch_ids' => $this->targetAudience === 'batch' ? $this->targetBatchIds : null,
                'is_pinned'        => $this->isPinned,
                'published_at'     => $this->publishedAt ?: now(),
                'expires_at'       => $this->expiresAt ?: null,
                'created_by'       => auth()->id(),
            ]
        );

        session()->flash('success', $this->editingId ? 'Announcement updated.' : 'Announcement published.');
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Announcement::findOrFail($id)->delete();
        session()->flash('success', 'Announcement deleted.');
    }

    public function togglePin(int $id): void
    {
        $ann = Announcement::findOrFail($id);
        $ann->update(['is_pinned' => !$ann->is_pinned]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = $this->body = $this->publishedAt = $this->expiresAt = '';
        $this->targetAudience = 'all';
        $this->targetBatchIds = [];
        $this->isPinned = false;
    }

    public function render()
    {
        $announcements = Announcement::with('createdBy')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->latest('published_at')
            ->paginate(15);

        $batches = Batch::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.communication.announcements', [
            'announcements' => $announcements,
            'batches'       => $batches,
            'totalCount'    => Announcement::count(),
            'pinnedCount'   => Announcement::where('is_pinned', true)->count(),
            'activeCount'   => Announcement::published()->count(),
        ])->layout('layouts.admin', ['title' => 'Announcements']);
    }
}
