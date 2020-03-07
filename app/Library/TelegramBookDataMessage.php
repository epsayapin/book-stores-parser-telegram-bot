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
		$message = self::createBookCardText($bookCard);

		$keyboard[] = [['text' => 'Наличие', 'callback_data' => 'storesListInStock,' . $bookCard->code],['text' => 'Открыть на сайте', 'url' => ChcnnParsing::BOOKCARD_URL . "$bookCard->code"]];
		
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
		$keyboard[][] = ["text" => "Source: " . $searchResult->source, "callback_data" => "empty"];

		if(session('searchResult'))
			{
			$cache = session('searchResult');
			$keyboard[][] = ["text" => "Cached data: " . $cache->query . ", " . $cache->currentPage . ", " . $cache->currentPart . ", " . $cache->totalParts . ", " . $cache->totalPages  . ", " . count($cache->bookList), "callback_data" => "empty"];				
			}

		$keyboard[][] = ["text" => "Current data: " . $searchResult->query . ", " . $searchResult->currentPage . ", " . $searchResult->currentPart . ", " . $searchResult->totalParts . ", " . $searchResult->totalPages  . ", " . count($searchResult->bookList), "callback_data" => "empty"];				
			

		$i = (($searchResult->currentPage - 1) * 24) + (($searchResult->currentPart - 1) * 6) + 1;
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

		$navButtons = [];

		for($i = 1; $i <= 5; $i++)
		{
			$navButtons[] = ['text' => "\xE2\x9A\xAA", 'callback_data' => 'empty'];
		}

		$navButtons[2]["text"] = "\xE2\x9C\x8C"; //знак v пальцами

		//кнопка вперёд
		if (($searchResult->totalPages > $searchResult->currentPage)||($searchResult->totalParts > $searchResult->currentPart))
			{
				$navButtons[3] = ['text' => "\xE2\x96\xB6", 'callback_data' => 'searchResult,' . ($searchResult->currentPage . "," . ($searchResult->currentPart + 1))];
			}

		//кнопка назад
		if (($searchResult->currentPage > 1)||($searchResult->currentPart > 1))
			{
				$navButtons[1] = ['text' => "\xE2\x97\x80", 'callback_data' => 'searchResult,' . $searchResult->currentPage . "," . ($searchResult->currentPart - 1)];
			}

		$keyboard[] = $navButtons;

		$replyMarkup = Telegram::replyKeyboardMarkup([
						'inline_keyboard' => $keyboard
						]);

		return $replyMarkup;
	}

	public static function addStoresListInStock($chatId, $messageId, $code)
	{
		//$storesList = ChcnnParsing::getStoresListInStock($code);
		
		if(session($code))
		{
			$bookCard = session($code);
		}else{
		$bookCard = ChcnnParsing::getBookCard($code);
		}
		$storesList = ChcnnParsing::getStoresListInStock($code);

		$message = self::createBookCardText($bookCard);

		$message .= "\n*Магазины*\n";

		date_default_timezone_set('Europe/Samara');
		$date = date('m/d/Y H:i:s', time());

		$message .= "Состояние на " . $date . " \n";
		

		if(count($storesList) > 0)
		{
			foreach($storesList as $store)
			{
				$message .= $store["title"] . "\n";
				$message .= $store["phone"] . "\n";
			}
				$message .= "\n_Важно уточнить фактическое наличие звонком_";
		}else{
			$message .= "Не числится в наличии";
		} 


		$keyboard[] = [['text' => 'Наличие', 'callback_data' => 'storesListInStock,' . $bookCard->code], ['text' => 'Открыть на сайте', 'url' => ChcnnParsing::BOOKCARD_URL . "$bookCard->code"]];

		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' =>  $keyboard
		]);
		
		$response = Telegram::editMessageText([
			'chat_id' => $chatId,
			'message_id' => $messageId,
			'text' => $message,
			'parse_mode' => 'Markdown',
			'reply_markup' => $replyMarkup
		]);

	}

	private static function createBookCardText(BookCard $bookCard)
	{
		$message = "";
		//$message .= "Source: " . $bookCard->source . "\n";
		$message .= "_" . $bookCard->author . "_, код товара: $bookCard->code\n";
		$message .= "*$bookCard->title*\n";
		$message .= "📕$bookCard->coverFormat\n";
		$message .= "📃" . $bookCard->getCountPages() . "\n";
		$message .= "Цена в интернет магазине: " . $bookCard->getInternetPrice() . "\n";
		$message .= "Цена в локальном магазине: " . $bookCard->getLocalPrice() . "\n";

		return $message;
	}

}