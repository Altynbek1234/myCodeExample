<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\InstagramChat;
use App\Models\Messenger;
use App\Models\MessengerChat;
use App\Models\WhatsappChat;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getAllAccounts()
    {
        $accounts = Account::where('status', true)->with('messenger')->get();
        foreach($accounts as $account) {
            if($account->messenger_id === Messenger::WHATSAPP_ID) {
                $account->chats = $account->whatsappChats;
            } elseif ($account->messenger_id === Messenger::MESSENGER_ID) {
                $account->chats = $account->messengerChats;
            } elseif ($account->messenger_id === Messenger::INSTAGRAM_ID) {
                $account->chats = $account->instagramChats;
            } elseif ($account->messenger_id === Messenger::TELEGRAM_ID) {
                $account->chats = $account->telegramChats;
            }
        }

        return $this->render($accounts);
    }
}
