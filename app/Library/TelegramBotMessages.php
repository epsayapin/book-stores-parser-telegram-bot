<?php
namespace App\Library;
use Telegram;

class TelegramBotMessages
{
	public static function showSearchResult($chatId)
	{
		$keyboard = [
			    ['7', '8', '9'],
			    ['4', '5', '6'],
			    ['1', '2', '3'],
			         ['0']
			];

		$replyMarkup = [
			'keyboard' => $keyboard,
			'resize_keyboard' => true,
			'one_time_keyboard' => true
		];

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Hello, world!',
			'reply_markup' => $keyboard
		]);

		$getMessageId = $response->getMessageId();
		
	}

}