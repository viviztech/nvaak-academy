<div class="max-w-3xl mx-auto space-y-6">

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Step 1: Student Search (shown when no assignment selected) --}}
    @if (! $assignment)
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Find Student</h2>
            <p class="text-sm text-gray-500 mb-4">Search by student name, phone, or student code to collect a fee payment.</p>

            <div class="relative">
                <input wire:model.live.debounce.300ms="searchQuery"
                    type="text"
                    placeholder="Search student name, phone, or code..."
                    class="w-full px-4 py-3 pr-10 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                <svg class="absolute right-3 top-3.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Search results --}}
            @if ($studentResults->isNotEmpty())
                <div class="mt-3 space-y-2">
                    @foreach ($studentResults as $student)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-[#1E3A5F] flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $student->user?->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->student_code }} &nbsp;•&nbsp; {{ $student->user?->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            {{-- Fee assignments for this student --}}
                            @if ($student->feeAssignments->isEmpty())
                                <p class="text-xs text-gray-400 pl-12">No fee assignments found.</p>
                            @else
                                <div class="pl-12 space-y-1">
                                    @foreach ($student->feeAssignments as $fa)
                                        <button wire:click="selectAssignment({{ $fa->id }})"
                                            class="w-full flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-orange-50 hover:border-orange-300 transition-colors text-left">
                                            <div>
                                                <span class="text-sm font-medium text-gray-800">{{ $fa->feeStructure?->name ?? 'Fee' }}</span>
                                                <span class="text-xs text-gray-500 ml-2">{{ $fa->batch?->name }}</span>
                                            </div>
                                            <span class="text-xs font-semibold text-[#F97316]">Collect →</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif (strlen($searchQuery) >= 2)
                <p class="mt-3 text-sm text-gray-400 text-center py-4">No students found for "{{ $searchQuery }}"</p>
            @endif
        </div>
    @endif

    {{-- Step 2: Payment form (shown after student/assignment selected) --}}
    @if ($assignment)
        {{-- Student Info Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Student Fee Details</h2>
                <button wire:click="clearAssignment"
                    class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                    ← Change Student
                </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Student Name</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $assignment->student->user->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Student Code</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $assignment->student->student_code ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Batch</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $assignment->batch->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Fee Structure</p>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $assignment->feeStructure->name ?? '—' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Fee</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">₹{{ number_format($assignment->final_amount, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Paid</p>
                    <p class="text-xl font-bold text-green-600 mt-1">₹{{ number_format($assignment->total_paid, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Balance</p>
                    <p class="text-xl font-bold mt-1 {{ $assignment->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        ₹{{ number_format($assignment->balance, 2) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Installment Selector --}}
        @if ($unpaidInstallments->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Select Installment (Optional)</h3>
                <div class="space-y-2">
                    @foreach ($unpaidInstallments as $installment)
                        <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors
                                      {{ $selectedInstallmentId == $installment->id ? 'border-orange-400 bg-orange-50' : 'border-gray-200' }}">
                            <input type="radio" wire:model.live="selectedInstallmentId"
                                   value="{{ $installment->id }}"
                                   class="text-orange-500 focus:ring-orange-300">
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $installment->name }}</p>
                                    <p class="text-xs text-gray-500">Due: {{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-gray-900">₹{{ number_format($installment->amount, 2) }}</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Payment Form --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 mb-4">Payment Details</h3>

            <div class="space-y-4">
                {{-- Amount --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Amount (₹) *</label>
                    <input wire:model="amount" type="number" step="0.01" min="0"
                           placeholder="Enter amount"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Payment Mode --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Payment Mode *</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['cash' => 'Cash', 'cheque' => 'Cheque', 'dd' => 'DD', 'upi' => 'UPI', 'online' => 'Online'] as $value => $label)
                            <label class="flex items-center gap-1.5 px-3 py-2 border rounded-lg cursor-pointer text-sm transition-colors
                                          {{ $payment_mode === $value ? 'border-orange-400 bg-orange-50 text-orange-700 font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                <input type="radio" wire:model.live="payment_mode" value="{{ $value }}" class="sr-only">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Cheque / DD fields --}}
                @if (in_array($payment_mode, ['cheque', 'dd']))
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-xl">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Bank Name</label>
                            <input wire:model="bank_name" type="text" placeholder="Bank name"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                            @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        @if ($payment_mode === 'cheque')
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Cheque Number</label>
                                <input wire:model="cheque_number" type="text" placeholder="Cheque number"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                                @error('cheque_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Cheque Date</label>
                                <input wire:model="cheque_date" type="date"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                                @error('cheque_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                @endif

                {{-- UPI / Online --}}
                @if (in_array($payment_mode, ['upi', 'online']))
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Transaction Reference</label>
                        <input wire:model="transaction_reference" type="text" placeholder="UPI/Transaction ID"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
                    </div>
                @endif

                {{-- Remarks --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Remarks</label>
                    <textarea wire:model="remarks" rows="2" placeholder="Optional remarks..."
                              class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"></textarea>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button wire:click="pay"
                            wire:loading.attr="disabled"
                            class="w-full py-3 text-white font-semibold rounded-xl shadow-sm transition-opacity disabled:opacity-60"
                            style="background-color: #f97316;">
                        <span wire:loading.remove>Record Payment</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
