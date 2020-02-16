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
		$message .= "_" . $bookCard->author[0] . "_ $bookCard->code\n";
		$message .= "*$bookCard->title*\n";
		$message .= "📕$bookCard->coverFormat\n";
		$message .= "📃" . $bookCard->countPages . " с.\n";
		$message .= "Цена в интернет магазине: " . $bookCard->internetPrice . "р.\n";
		$message .= "Цена в локальном магазине: " . $bookCard->localPrice . "р.\n";

		$keyboard[][] = ['text' => 'Открыть на сайте', 'url' => ChcnnParsing::$bookcard_url . "$bookCard->code"];
		$keyboard[][] = ['text' => 'Наличие', 'callback_data' => 'storesListInStock,' . $bookCard->code];
		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' =>  $keyboard
		]);
		
		$response = Telegram::sendMessage([
								'chat_id' => $chatId,
								'text' => $message,
								'parse_mode' => 'Markdown',
								'reply_markup' => $replyMarkup
								]);

		
	}

	public static function createReplyMarkup(SearchResult $searchResult)
	{

		$keyboard = [];

		$i = 1;
		foreach($searchResult->bookList as $book)
			{
				$keyboard[][] = [	'text' => "$i. " . $book['title'], 
									'callback_data' => 'bookCard,' . $book['code']];
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


		if (($searchResult->totalPages > $searchResult->currentPage)||($searchResult->totalPages > $searchResult->currentPage))
			{
				$navButtons[1] = ['text' => '>', 'callback_data' => 'searchResult,' . ($searchResult->currentPage . "," . ($searchResult->currentPart + 1))];
			}

		if (($searchResult->currentPage > 1)||($searchResult->currentPart > 1))
			{
				$navButtons[0] = ['text' => '<', 'callback_data' => 'searchResult,' . $searchResult->currentPage . "," . ($searchResult->currentPart - 1)];
			}

		$keyboard[] = $navButtons;

		$replyMarkup = Telegram::replyKeyboardMarkup([
						'inline_keyboard' => $keyboard
						]);

		return $replyMarkup;
	}

	public static function addStoresListInStock($chatId, $messageId, $storesList)
	{
		//$storesList = ChcnnParsing::getStoresListInStock($code);


		$text = "";

		foreach($storesList as $store)
		{
			$text .= $store["title"] . "\n";
		} 

		$response = Telegram::editMessageText([

			'chat_id' => $chatId,
			'message_id' => $messageId,
			'text' => $text

		]);

	}
}