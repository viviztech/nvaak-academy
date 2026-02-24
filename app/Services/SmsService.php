<?php

namespace App\Services;

use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phone, string $message, string $type = 'general'): bool
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) == 10) {
            $phone = '91' . $phone;
        }

        // Log notification
        NotificationLog::create([
            'notifiable_type'   => 'sms',
            'notifiable_id'     => 0,
            'notification_type' => $type,
            'channel'           => 'sms',
            'body'              => $message,
            'metadata'          => ['phone' => $phone],
            'status'            => 'pending',
        ]);

        if (! config('services.msg91.key')) {
            Log::info("SMS (dev mode) to $phone: $message");

            return true;
        }

        try {
            $resp = Http::post('https://api.msg91.com/api/v5/flow/', [
                'template_id'      => config('services.msg91.template_id'),
                'short_url'        => '0',
                'realTimeResponse' => '1',
                'authkey'          => config('services.msg91.key'),
                'mobiles'          => $phone,
                'VAR1'             => $message,
            ]);

            return $resp->successful();
        } catch (\Exception $e) {
            Log::error('SMS send failed: ' . $e->getMessage());

            return false;
        }
    }

    public function sendBulk(array $phones, string $message, string $type = 'bulk'): void
    {
        foreach (array_chunk($phones, 50) as $chunk) {
            foreach ($chunk as $phone) {
                $this->send($phone, $message, $type);
            }
        }
    }
}
