<?php
namespace App\Library;
use Telegram;
use App\Library\BookCard;
use App\Library\searchResult;

class TelegramBookDataMessage
{
	public static function showSearchResult($chatId, $searchResult)
	{

		$replyMarkup = self::createReplyMarkup($searchResult);

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Вот что удалось найти',
			'reply_markup' => $replyMarkup
		]);

		$getMessageId = $response->getMessageId();
		/*
		Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => "Current $searchResult->currentPage, Total $searchResult->countPages ID $getMessageId"
		]);
		*/
	}

	public static function showBookCard($chatId, BookCard $bookCard)
	{
		$message = "";
		$message .= "_" . $bookCard->author[0] . "_\n";
		$message .= "*$bookCard->title*\n";
		$message .= $bookCard->price . "р.\n";

		$response = Telegram::sendMessage([
								'chat_id' => $chatId,
								'text' => $message,
								'parse_mode' => 'Markdown'
								]);
		
	}

	public static function createReplyMarkup(SearchResult $searchResult)
	{

		$keyboard = [];

		$i = 1;
		foreach($searchResult->bookList as $book)
		{
			$keyboard[][] = ['text' => "$i. " . $book['title'], 'callback_data' => 'bookCard,' . $book['code']];
			$i++;
		}

		$fillStr = "................................................................................";

		$keyboard[][] = ["text" => $fillStr, 'callback_data' => "epmty"];

		/*
		$buttonPages = [
				["text" => "Count $searchResult->countPages", "callback_data" => 'empty'],
				["text" => "Current $searchResult->currentPage", "callback_data" => 'empty']
		];
		$keyboard[] = $buttonPages;
		*/

		$navButtons = [
					['text' => '*', 'callback_data' => 'empty'],
					['text' => '*', 'callback_data' => 'empty']
		];


		if ($searchResult->currentPage < $searchResult->countPages)
		{
			$navButtons[1] = ['text' => '>', 'callback_data' => 'searchResult,' . ($searchResult->currentPage + 1 . "," . $searchResult->resultPart + 1)];
		}

		if (($searchResult->currentPage > 1)||($searchResult->resultPart > 1))
		{
			$navButtons[0] = ['text' => '<', 'callback_data' => 'searchResult,' . ($searchResult->currentPage - 1 . "," . $searchResult->resultPart - 1)];
		}

		$keyboard[] = $navButtons;


		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' => $keyboard
		]);

		return $replyMarkup;
	}

	public function createSearchResultNavButtons($currentPage, $currentPart, $totalPages, $totalPartsOnPage): array
	{

	}

}