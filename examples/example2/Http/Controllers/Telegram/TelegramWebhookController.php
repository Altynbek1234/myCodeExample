<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\Messenger;
use App\Models\WebhookLog;
use App\Services\Telegram\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();

        $log = new WebhookLog();
        $log->status = false;
        $log->log = $data;
        $log->messenger_id = Messenger::TELEGRAM_ID;
        $log->save();

        try {
            $telegramBot = new TelegramBot($data);
            $telegramBot->start();
        } catch (\Throwable $throwable) {
            Log::info('a', [$throwable->getMessage()]);
        }

    }

}
