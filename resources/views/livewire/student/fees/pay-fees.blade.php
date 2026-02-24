<div
    x-data="{}"
    @open-razorpay.window="
        var options = {
            key: $event.detail.key,
            amount: $event.detail.amount * 100,
            currency: 'INR',
            name: 'NVAAK IAS & NEET Academy',
            description: 'Fee Payment',
            order_id: $event.detail.order_id,
            handler: function(response) {
                $wire.verifyPayment(
                    response.razorpay_order_id,
                    response.razorpay_payment_id,
                    response.razorpay_signature
                );
            },
            prefill: {
                name: '{{ auth()->user()->name ?? '' }}',
                email: '{{ auth()->user()->email ?? '' }}'
            },
            theme: { color: '#f97316' }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    "
>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if (!$assignment)
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v13a2 2 0 01-2 2h-3"/>
            </svg>
            <p class="text-gray-600 font-medium">No fee assignment found</p>
            <p class="text-sm text-gray-400 mt-1">Please contact the administration office.</p>
        </div>
    @else
        {{-- Fee Summary Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Fee Summary</h2>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Total Fee</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($assignment->final_amount, 2) }}</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <p class="text-xs text-green-600 uppercase tracking-wider">Paid</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">₹{{ number_format($assignment->total_paid, 2) }}</p>
                </div>
                <div class="text-center p-4 {{ $assignment->balance > 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-xl">
                    <p class="text-xs {{ $assignment->balance > 0 ? 'text-red-600' : 'text-green-600' }} uppercase tracking-wider">Balance</p>
                    <p class="text-2xl font-bold {{ $assignment->balance > 0 ? 'text-red-600' : 'text-green-600' }} mt-1">
                        ₹{{ number_format($assignment->balance, 2) }}
                    </p>
                </div>
            </div>

            {{-- Progress bar --}}
            @php
                $progress = $assignment->final_amount > 0
                    ? min(100, ($assignment->total_paid / $assignment->final_amount) * 100)
                    : 0;
            @endphp
            <div>
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Payment Progress</span>
                    <span>{{ number_format($progress, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all duration-500 {{ $progress >= 100 ? 'bg-green-500' : 'bg-orange-500' }}"
                         style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        {{-- Installments Table --}}
        @if ($installments->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Installments</h3>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Name</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Due Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($installments as $idx => $installment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $installment['name'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $installment['due_date']->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">₹{{ number_format($installment['amount'], 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($installment['is_paid'])
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Paid</span>
                                    @elseif ($installment['is_overdue'])
                                        <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">Overdue</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">Due</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if (!$installment['is_paid'])
                                        <button wire:click="initiatePayment({{ $installment['id'] }})"
                                                wire:loading.attr="disabled"
                                                class="px-4 py-1.5 text-white text-xs font-medium rounded-lg transition-opacity disabled:opacity-60"
                                                style="background-color: #f97316;">
                                            Pay Now
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Single payment option --}}
            @if (!$assignment->is_fully_paid)
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Pay Full Amount</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Outstanding balance: <strong>₹{{ number_format($assignment->balance, 2) }}</strong>
                    </p>
                    <button wire:click="initiatePayment(0)"
                            class="px-6 py-2.5 text-white font-medium rounded-xl"
                            style="background-color: #f97316;">
                        Pay ₹{{ number_format($assignment->balance, 2) }} Now
                    </button>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-800 font-semibold">All fees paid!</p>
                    <p class="text-green-600 text-sm mt-1">Your fee account is fully settled.</p>
                </div>
            @endif
        @endif
    @endif
</div>
