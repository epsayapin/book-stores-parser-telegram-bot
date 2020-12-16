<?php


namespace App\Services;

use Telegram;
use App\Services\SettingsService;
use App\Services\BookStoreParsers\ChaconneBookStoreParser;
use App\BookCard;

class TelegramUpdatesHandlerService
{
    const GET_BOOKCARD_METHOD = "getBookCard";
    const GET_SEARCH_RESULTS_METHOD = "getSearchResults";
    const SEARCH_RESULTS_DIVIDER = "..................................................";

    const EMOJI_ARROW_ON_RIGHT = "\xE2\x96\xB6";
    const EMOJI_ARROW_ON_LEFT = "\xE2\x97\x80";
    const EMOJI_STUB = "\xE2\x9A\xAA";
    const EMOJI_DECORATION = "\xE2\x9C\x8C";

    private $bookStoreParser;

    public function __construct()
    {
        $this->bookStoreParser = SettingsService::getParser();
    }

    public function handle($update)
    {
        if ($this->isCallback($update)) {
            $this->handleCallback($update);

            return;
        }

        if ($this->isTextMessage($update)) {
            $this->saveSearchQueryToSession($update);
            $this->sendSearchResults($update);

            return;
        }
    }

    private function handleCallback($update)
    {
        //callback params - [ "0" => method name, "1"... => params]
        $callbackParams = $this->extractCallbackParams($update);

        switch ($callbackParams[0]) {
            case self::GET_SEARCH_RESULTS_METHOD:
                $this->handleCallbackGetSearchResultsMethod($update);
                break;
            case self::GET_BOOKCARD_METHOD:
                $this->handleCallbackGetBookCardMethod($update);
                break;
            default:
                //Telegram::answerCallbackQuery();
                break;
        }
    }

    private function extractCallbackParams($update)
    {
        return explode(',', $update['callback_query']["data"]);
    }

    private function handleCallbackGetSearchResultsMethod($update)
    {
        $callbackParams = $this->extractCallbackParams($update);

        $pageNumber = $callbackParams[1];
        if (! empty($this->getSearchQuery())) {
            Telegram::editMessageText([
                "chat_id" => $update["callback_query"]['message']['chat']['id'],
                "message_id" => $update["callback_query"]['message']['message_id'],
                "text" => 'Вот что удалось найти',
                "reply_markup" => $this->createSearchResultsReplyMarkup(
                    $this->bookStoreParser->getSearchResults($this->getSearchQuery(), $pageNumber)
                )
            ]);
        }
    }

    private function handleCallbackGetBookCardMethod($update)
    {
        $callbackParams = $this->extractCallbackParams($update);

        if (isset($callbackParams[1])) {
            $code = $callbackParams[1];
            $bookCard = $this->bookStoreParser->getBookCard($code);
            $this->sendBookCard($update["callback_query"]['message']['chat']['id'], $bookCard);
        }
    }

    private function sendSearchResults($update)
    {
        Telegram::sendMessage([
            'chat_id' => $update['message']['chat']['id'],
            'text' => 'Вот что удалось найти',
            'reply_markup' => $this->createSearchResultsReplyMarkup(
                $this->bookStoreParser->getSearchResults($update["message"]["text"])
            )
        ]);
    }

    private function sendBookCard($chatId, $bookCard)
    {
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $bookCard->getCardAsMessage(),
            'parse_mode' => 'Markdown',
            'reply_markup' => Telegram::replyKeyboardMarkup([
                'inline_keyboard' =>  [
                    [[
                        'text' => 'Открыть на сайте',
                        'url' => ChaconneBookStoreParser::BOOK_CARD_URL . $bookCard->getCode()
                    ]]
                ]
            ])
        ]);
    }

    private function createSearchResultsReplyMarkup($searchResult)
    {
        $keyboard = [];

        foreach($searchResult["books"] as $i => $book) {
            $keyboard[][] = [
                'text' =>
                    ($i + 1)
                    + ($this->bookStoreParser->getBooksOnSearchPageCount()) * ($searchResult["pageNumber"] - 1)
                    .
                    ". " . $book['title'],
                'callback_data' => 'getBookCard,' . $book['code']];
        }

        $keyboard[][] = [
            "text" => self::SEARCH_RESULTS_DIVIDER,
            'callback_data' => "empty"
        ];

        $navButtons = [];

        for($i = 1; $i <= 5; $i++)
        {
            $navButtons[] = [
                'text' => self::EMOJI_STUB,
                'callback_data' => 'empty'];
        }

        $navButtons[2]["text"] = self::EMOJI_DECORATION; //эмодзи с пальцами

        if ($searchResult["pagesCount"] > $searchResult["pageNumber"]) {
            $navButtons[3] = [
                'text' => self::EMOJI_ARROW_ON_RIGHT,
                'callback_data' =>
                    self::GET_SEARCH_RESULTS_METHOD . ','
                    . ($searchResult["pageNumber"] + 1)
            ];
        }

        if ($searchResult["pageNumber"] > 1) {
            $navButtons[1] = [
                'text' => self::EMOJI_ARROW_ON_LEFT,
                'callback_data' =>
                    self::GET_SEARCH_RESULTS_METHOD . ','
                    . ($searchResult["pageNumber"] - 1) . ","
            ];
        }

        $keyboard[] = $navButtons;

        $replyMarkup = Telegram::replyKeyboardMarkup([
            'inline_keyboard' => $keyboard
        ]);

        return $replyMarkup;
    }

    private function isCallback($update)
    {
        return isset($update["callback_query"]);
    }

    private function isTextMessage($update)
    {
        return (isset($update["message"]["text"]) && !(isset($update["message"]["entities"][0]['type'])));
    }

    private function saveSearchQueryToSession($update)
    {
        if (isset($update["message"]["text"])) {
            session(["searchQuery" => $update["message"]["text"]]);
        }
    }

    private function getSearchQuery()
    {
       return session("searchQuery");
    }
}