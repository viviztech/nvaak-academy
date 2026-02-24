<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif

    {{-- Stats + Add button --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-6">
            <div>
                <p class="text-sm text-gray-500">Total Faculty</p>
                <p class="text-2xl font-bold text-[#1E3A5F]">{{ $totalFaculty }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $activeFaculty }}</p>
            </div>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-2 rounded-lg bg-[#F97316] px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Faculty
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
        <input wire:model.live.debounce.300ms="search" type="text"
            placeholder="Search faculty by name or email..."
            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Faculty</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Specialization</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qualification</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Batches</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($facultyList as $faculty)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-[#1E3A5F] flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($faculty->user?->name ?? 'F', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $faculty->user?->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $faculty->user?->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $faculty->specialization ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $faculty->qualification ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $faculty->batches->count() }} batch(es)</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $faculty->user?->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                {{ $faculty->user?->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit({{ $faculty->id }})"
                                    class="text-xs px-3 py-1 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $faculty->id }})"
                                    class="text-xs px-3 py-1 rounded-lg border
                                        {{ $faculty->user?->is_active ? 'border-red-300 text-red-600 hover:bg-red-50' : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                                    {{ $faculty->user?->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-sm">No faculty members found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($facultyList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $facultyList->links() }}</div>
        @endif
    </div>

    {{-- Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4" wire:click.stop>
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">{{ $editingId ? 'Edit Faculty' : 'Add Faculty' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input wire:model="name" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @if (!$editingId)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input wire:model="email" type="email" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input wire:model="phone" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                            <input wire:model="specialization" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" placeholder="e.g. Biology" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                            <input wire:model="qualification" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" placeholder="e.g. M.Sc, B.Ed" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Experience (years)</label>
                        <input wire:model="experience" type="number" min="0"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="save"
                        class="px-4 py-2 text-sm font-semibold text-white bg-[#1E3A5F] rounded-lg hover:bg-[#162d4a]">
                        {{ $editingId ? 'Update' : 'Add Faculty' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
