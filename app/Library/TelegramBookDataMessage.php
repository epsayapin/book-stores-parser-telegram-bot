<?php
namespace App\Library;
use Telegram;
use App\Library\BookCard;
use App\Library\searchResult;

class TelegramBookDataMessage
{
	public static function showSearchResult($chatId, $searchResult)
	{


		$keyboard = [];


		
		$i = 1;
		foreach($searchResult->bookList as $book)
		{
			$keyboard[][] = ['text' => "$i. " . $book['title'], 'callback_data' => $book['code']];
			$i++;
		}

		$fillStr = "................................................................................";

		$keyboard[][] = ["text" => $fillStr, 'callback_data' => "epmty"];

		$buttonPages = [
				["text" => "Count $searchResult->countPages", "callback_data" => 'empty'],
				["text" => "Current $searchResult->currentPage", "callback_data" => 'empty']
		];
		$keyboard[] = $buttonPages;

		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' => $keyboard
		]);

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Вот что удалось найти',
			'reply_markup' => $replyMarkup
		]);

		$getMessageId = $response->getMessageId();
		
	}

	public static function showBookCard($chatId, BookCard $bookCard)
	{
		$message = "";
		$message .= "Название - $bookCard->title"
		$message .= "Автор - $bookCard->author[0]";
		$message .= "Цена = $bookCard->price";

		
	}

}