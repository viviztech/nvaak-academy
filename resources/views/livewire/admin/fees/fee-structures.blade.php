<div>
    {{-- Flash Message --}}
    @if (session()->has('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Fee Structures</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage fee plans for each batch</p>
        </div>
        <button wire:click="$set('showModal', true)"
                class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-xl shadow-sm transition-colors"
                style="background-color: #f97316;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Fee Structure
        </button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search fee structures..."
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
        </div>
        <select wire:model.live="batchFilter"
                class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
            <option value="">All Batches</option>
            @foreach ($batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Amount</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Installments</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Valid From</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($feeStructures as $fs)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $fs->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $fs->batch->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $typeColors = [
                                    'tuition'      => 'bg-blue-100 text-blue-700',
                                    'registration' => 'bg-purple-100 text-purple-700',
                                    'exam'         => 'bg-yellow-100 text-yellow-700',
                                    'material'     => 'bg-green-100 text-green-700',
                                    'hostel'       => 'bg-pink-100 text-pink-700',
                                    'other'        => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$fs->fee_type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($fs->fee_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900">
                            ₹{{ number_format($fs->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center text-gray-600">
                            @if ($fs->installments_allowed)
                                <span class="text-green-600 font-medium">{{ $fs->installment_count }}x</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $fs->valid_from->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleActive({{ $fs->id }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none
                                           {{ $fs->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform
                                             {{ $fs->is_active ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                            </button>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="edit({{ $fs->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 text-xs font-medium px-2 py-1 rounded hover:bg-indigo-50">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $fs->id }})"
                                        wire:confirm="Delete this fee structure?"
                                        class="text-red-600 hover:text-red-800 text-xs font-medium px-2 py-1 rounded hover:bg-red-50">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v13a2 2 0 01-2 2h-3"/>
                            </svg>
                            <p class="font-medium">No fee structures found</p>
                            <p class="text-xs mt-1">Create your first fee structure to get started</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($feeStructures->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $feeStructures->links() }}
            </div>
        @endif
    </div>

    {{-- Modal --}}
    <div x-data="{ open: @entangle('showModal') }"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$wire.resetForm()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $editingId ? 'Edit Fee Structure' : 'New Fee Structure' }}
                </h3>
                <button @click="$wire.resetForm()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="px-6 py-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Name *</label>
                        <input wire:model="name" type="text" placeholder="e.g. NEET Foundation 2026 Tuition"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Batch *</label>
                        <select wire:model="batch_id"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                            <option value="">Select Batch</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                        @error('batch_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Fee Type *</label>
                        <select wire:model="fee_type"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                            <option value="tuition">Tuition</option>
                            <option value="registration">Registration</option>
                            <option value="exam">Exam</option>
                            <option value="material">Material</option>
                            <option value="hostel">Hostel</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Total Amount (₹) *</label>
                        <input wire:model="total_amount" type="number" step="0.01" min="0" placeholder="0.00"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('total_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Valid From *</label>
                        <input wire:model="valid_from" type="date"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        @error('valid_from') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Valid To</label>
                        <input wire:model="valid_to" type="date"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>

                    <div class="sm:col-span-2 flex items-center gap-6 py-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model.live="installments_allowed" type="checkbox"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-300">
                            <span class="text-sm text-gray-700 font-medium">Allow Installments</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model.live="discount_allowed" type="checkbox"
                                   class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-300">
                            <span class="text-sm text-gray-700 font-medium">Allow Discount</span>
                        </label>
                    </div>

                    @if ($installments_allowed)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Number of Installments</label>
                            <input wire:model="installment_count" type="number" min="2" max="12"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            <p class="text-xs text-gray-400 mt-1">Installments will be split equally and auto-created.</p>
                        </div>
                    @endif

                    @if ($discount_allowed)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Max Discount (%)</label>
                            <input wire:model="max_discount_percent" type="number" step="0.01" min="0" max="100"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                        </div>
                    @endif

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" placeholder="Optional description..."
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" @click="$wire.resetForm()"
                            class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 text-sm text-white font-medium rounded-lg shadow-sm"
                            style="background-color: #f97316;">
                        Save Fee Structure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
