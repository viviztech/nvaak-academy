<?php

namespace App\Livewire\Admin\Faculty;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 15;

    // Create/Edit modal
    public bool $showModal = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $specialization = '';
    public string $qualification = '';
    public string $experience = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEdit(int $facultyId): void
    {
        $faculty = Faculty::with('user')->findOrFail($facultyId);
        $this->editingId = $facultyId;
        $this->name           = $faculty->user?->name ?? '';
        $this->email          = $faculty->user?->email ?? '';
        $this->phone          = $faculty->user?->phone ?? '';
        $this->specialization = $faculty->specialization ?? '';
        $this->qualification  = $faculty->qualification ?? '';
        $this->experience     = (string) ($faculty->experience_years ?? '');
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . (Faculty::find($this->editingId)?->user_id ?? 'NULL'),
            'phone' => 'required|string|max:20',
        ]);

        if ($this->editingId) {
            $faculty = Faculty::with('user')->findOrFail($this->editingId);
            $faculty->user?->update([
                'name'  => $this->name,
                'phone' => $this->phone,
            ]);
            $faculty->update([
                'specialization'   => $this->specialization,
                'qualification'    => $this->qualification,
                'experience_years' => $this->experience ?: null,
            ]);
        } else {
            $user = User::create([
                'name'              => $this->name,
                'email'             => $this->email,
                'phone'             => $this->phone,
                'password'          => Hash::make('Faculty@123'),
                'is_active'         => true,
                'email_verified_at' => now(),
                'institute_id'      => auth()->user()->institute_id,
            ]);
            $user->assignRole('faculty');
            Faculty::create([
                'user_id'          => $user->id,
                'institute_id'     => auth()->user()->institute_id,
                'specialization'   => $this->specialization,
                'qualification'    => $this->qualification,
                'experience_years' => $this->experience ?: null,
                'is_active'        => true,
            ]);
        }

        session()->flash('success', $this->editingId ? 'Faculty updated.' : 'Faculty added. Default password: Faculty@123');
        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $facultyId): void
    {
        $faculty = Faculty::with('user')->findOrFail($facultyId);
        $faculty->user?->update(['is_active' => !$faculty->user->is_active]);
        session()->flash('success', 'Faculty status updated.');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = $this->email = $this->phone = '';
        $this->specialization = $this->qualification = $this->experience = '';
    }

    public function render()
    {
        $faculty = Faculty::with(['user', 'batches'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($uq) =>
                $uq->where('name', 'like', "%{$this->search}%")
                   ->orWhere('email', 'like', "%{$this->search}%")
            ))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.faculty.index', [
            'facultyList'   => $faculty,
            'totalFaculty'  => Faculty::count(),
            'activeFaculty' => Faculty::whereHas('user', fn($q) => $q->where('is_active', true))->count(),
        ])->layout('layouts.admin', ['title' => 'Faculty']);
    }
}
