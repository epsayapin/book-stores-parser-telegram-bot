<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
class TelegramBotMessagesController extends Controller
{
    //

	public function test()
	{
		$response = Telegram::getMe();

		$botId = $response->getId();
		$firstName = $response->getFirstName();
		$username = $response->getUsername();

		return $response;
	}
}
