<?php

namespace App\Http\Controllers\Telegram;

use App\Events\Telegram\NewTelegramMessage;
use App\Http\Controllers\Controller;
use App\Models\MessageStatus;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use App\Services\Telegram\TelegramSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelegramChatController extends Controller
{
    public function getChats($accountId)
    {
        $chats = TelegramChat::where('account_id', $accountId)->get();

        return $this->render($chats);
    }

    public function getChatMessages($chatId)
    {
        $messages = TelegramMessage::where('chat_id', $chatId)
            ->with('user')
            ->with('attachments')
            ->with('replyMessage')
            ->get()->keyBy('id');

        return $this->render($messages);
    }

    public function addNewMessage($chatId)
    {
        $chat = TelegramChat::getChatById($chatId);

        $telegramSender = new TelegramSender($chat);
        $telegramSender->sendTextMessage();
    }

    public function addNewFilesMessage($chatId, Request $request)
    {
        $chat = TelegramChat::getChatById($chatId);
        $telegramSender = new TelegramSender($chat);
        $telegramSender->sendFileMessages();
    }

    public function getUnreadMessagesCount($chatId)
    {
        $count = TelegramMessage::chatUnreadMessagesCount($chatId);

        return $this->render(['count' => $count]);
    }

    public function readMessage($messageId)
    {
        $message = TelegramMessage::find($messageId);
        $message->message_status_id = MessageStatus::READ_ID;
        $message->save();

        $telegramSender = new TelegramSender(TelegramChat::getChatById($message->chat_id));
        $telegramSender->markMessageAsRead($message);
    }
}
