<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Compose --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-5">Compose SMS</h3>

                {{-- Target audience --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach (['all' => 'Everyone', 'students' => 'Students', 'faculty' => 'Faculty', 'batch' => 'By Batch'] as $val => $label)
                            <label class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 cursor-pointer text-sm font-medium transition-colors
                                {{ $targetAudience === $val ? 'border-[#1E3A5F] bg-[#1E3A5F] text-white' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                                <input type="radio" wire:model.live="targetAudience" value="{{ $val }}" class="sr-only" />
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Batch selector --}}
                @if ($targetAudience === 'batch')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Batches</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($batches as $batch)
                                <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" wire:model="selectedBatchIds" value="{{ $batch->id }}"
                                        class="rounded border-gray-300 text-[#1E3A5F]" />
                                    <span class="text-sm text-gray-700">{{ $batch->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Message --}}
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <label class="block text-sm font-medium text-gray-700">Message *</label>
                        <span class="text-xs text-gray-400">
                            {{ $charCount }}/480 chars &nbsp;|&nbsp;
                            <span class="{{ $smsCount > 1 ? 'text-yellow-600 font-medium' : 'text-gray-400' }}">
                                {{ $smsCount }} SMS
                            </span>
                        </span>
                    </div>
                    <textarea wire:model.live="message" rows="5"
                        maxlength="480"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F] resize-none"
                        placeholder="Type your SMS message here...&#10;&#10;Variables you can use:&#10;{name} - Student/Faculty name&#10;{batch} - Batch name"></textarea>
                    @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button wire:click="preview"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                        Preview Recipients
                    </button>
                    <button wire:click="send"
                        wire:confirm="Send SMS to all selected recipients?"
                        wire:loading.attr="disabled"
                        class="px-6 py-2 text-sm font-semibold text-white bg-[#1E3A5F] rounded-lg hover:bg-[#162d4a] disabled:opacity-60 flex items-center gap-2">
                        <span wire:loading.remove wire:target="send">
                            <svg class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send SMS
                        </span>
                        <span wire:loading wire:target="send">Sending...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right: Preview + Tips --}}
        <div class="space-y-4">
            {{-- Recipients preview --}}
            @if ($previewRecipients !== null)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <h4 class="font-semibold text-gray-900 text-sm mb-3">
                        Recipients Preview
                        <span class="ml-2 inline-flex items-center rounded-full bg-[#1E3A5F] px-2 py-0.5 text-xs font-semibold text-white">
                            {{ $recipientCount }} total
                        </span>
                    </h4>
                    @if (!empty($previewRecipients))
                        <ul class="space-y-2">
                            @foreach ($previewRecipients as $name => $phone)
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $name }}</span>
                                    <span class="font-mono text-gray-500 text-xs">{{ $phone }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if ($recipientCount > 5)
                            <p class="text-xs text-gray-400 mt-3">... and {{ $recipientCount - 5 }} more</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-400">No recipients found for selected audience.</p>
                    @endif
                </div>
            @endif

            {{-- Tips --}}
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-5">
                <h4 class="font-semibold text-blue-800 text-sm mb-3">SMS Tips</h4>
                <ul class="space-y-2 text-xs text-blue-700">
                    <li>• 1 SMS = up to 160 characters</li>
                    <li>• Messages over 160 chars are split into multiple SMS and billed accordingly</li>
                    <li>• SMS is sent via MSG91. Ensure SMS credits are available</li>
                    <li>• Only active users with phone numbers will receive the SMS</li>
                    <li>• Always preview recipients before sending</li>
                </ul>
            </div>

            {{-- SMS character counter visual --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h4 class="font-semibold text-gray-900 text-sm mb-3">Character Usage</h4>
                <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                    <div class="h-2 rounded-full transition-all duration-300
                        {{ $charCount > 320 ? 'bg-red-500' : ($charCount > 160 ? 'bg-yellow-400' : 'bg-green-500') }}"
                        style="width: {{ min(($charCount / 480) * 100, 100) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>0</span>
                    <span class="text-blue-500">160</span>
                    <span class="text-yellow-500">320</span>
                    <span class="text-red-500">480</span>
                </div>
            </div>
        </div>
    </div>
</div>
