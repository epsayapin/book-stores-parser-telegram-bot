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

		$replyMarkup = Telegram::replyKeyboardMarkup([
			'keyboard' => $keyboard,
			'resize_keyboard' => true,
			'one_time_keyboard' => true
		]);

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Hello, world!',
			'reply_markup' => $replyMarkup
		]);

		$getMessageId = $response->getMessageId();
		
	}

}