<?php

namespace App\Livewire\Admin\Communication;

use Livewire\Component;
use App\Models\Batch;
use App\Models\User;
use App\Services\SmsService;

class BulkSms extends Component
{
    public string $targetAudience = 'all';
    public array $selectedBatchIds = [];
    public string $message = '';
    public int $charCount = 0;
    public bool $isSending = false;
    public ?array $previewRecipients = null;
    public int $recipientCount = 0;

    public function updatedMessage(): void
    {
        $this->charCount = strlen($this->message);
    }

    public function updatedTargetAudience(): void
    {
        $this->previewRecipients = null;
    }

    public function preview(): void
    {
        $this->previewRecipients = $this->getRecipients()->take(5)->pluck('phone', 'name')->toArray();
        $this->recipientCount = $this->getRecipients()->count();
    }

    public function send(): void
    {
        $this->validate([
            'message' => 'required|string|min:5|max:480',
            'targetAudience' => 'required|in:all,students,faculty,batch',
        ]);

        $recipients = $this->getRecipients()->pluck('phone')->filter()->toArray();

        if (empty($recipients)) {
            session()->flash('error', 'No recipients found for selected audience.');
            return;
        }

        try {
            $smsService = app(SmsService::class);
            $smsService->sendBulk($recipients, $this->message);
            session()->flash('success', 'SMS sent to ' . count($recipients) . ' recipients.');
        } catch (\Exception $e) {
            session()->flash('error', 'SMS service error: ' . $e->getMessage());
        }

        $this->message = '';
        $this->charCount = 0;
        $this->previewRecipients = null;
    }

    private function getRecipients()
    {
        $query = User::where('is_active', true)->whereNotNull('phone');

        return match ($this->targetAudience) {
            'students' => $query->role('student'),
            'faculty'  => $query->role('faculty'),
            'batch'    => $query->whereHas('student', fn($q) =>
                $q->whereHas('batches', fn($bq) =>
                    $bq->whereIn('batches.id', $this->selectedBatchIds)
                )
            ),
            default    => $query->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['super-admin', 'admin'])),
        };
    }

    public function render()
    {
        $batches = Batch::where('is_active', true)->orderBy('name')->get();
        $smsCount = ceil($this->charCount / 160);

        return view('livewire.admin.communication.bulk-sms', [
            'batches'  => $batches,
            'smsCount' => $smsCount,
        ])->layout('layouts.admin', ['title' => 'Bulk SMS']);
    }
}
