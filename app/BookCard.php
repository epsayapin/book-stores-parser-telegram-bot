<?php


namespace App;


class BookCard
{
    const ITALICS_TEXT_STYLE_SYMBOL = "_";
    const BOLD_TEXT_STYLE_SYMBOL = "*";

    const INFO_NOT_AVAIBLE_TEXT = "н/д";
    const AUTHOR_NOT_AVAIBLE_TEXT = "Автор неизвестен";
    const TITLE_NOT_AVAIBLE_TEXT = "Название неизвестно";
    const CURRENCY = "р.";
    const PAGES_COUNT = " c.";

    const NEXT_LINE_SYMBOL = "\n";

    private $author;
    private $title;
    private $coverFormat;
    private $countPages;
    private $internetPrice;
    private $localPrice;
    private $code;

    public function __construct(array $params)
    {
        foreach (array_keys($params) as $key) {
            if (property_exists($this, $key)) {
                $this->$key = $params[$key];
            }
        }
    }

    public function getCardAsMessage()
    {
        $message = "";
        $message .= $this->applyItalicsStyle($this->getAuthor()) . self::NEXT_LINE_SYMBOL;
        $message .= $this->applyBoldStyle($this->getTitle()) . self::NEXT_LINE_SYMBOL;
        $message .= "📕 " . $this->getCoverFormat() . self::NEXT_LINE_SYMBOL;
        $message .= "📃 " . $this->getCountPages() . self::NEXT_LINE_SYMBOL;
        $message .= "Цена в интернет магазине: " . $this->getInternetPrice() . self::NEXT_LINE_SYMBOL;
        $message .= "Цена в локальном магазине: " . $this->getLocalPrice() . self::NEXT_LINE_SYMBOL;
        $message .= "Код товара: " . $this->applyItalicsStyle($this->getCode());

        return $message;
    }

    public function getCode()
    {
        return $this->code;
    }

    private function getAuthor()
    {
        return isset($this->author) ?  $this->author : self::AUTHOR_NOT_AVAIBLE_TEXT;
    }

    private function getTitle()
    {
        return isset($this->coverFormat) ?  $this->title : self::TITLE_NOT_AVAIBLE_TEXT;
    }

    private function getCoverFormat()
    {
        return (isset($this->coverFormat) ? $this->coverFormat : self::INFO_NOT_AVAIBLE_TEXT);
    }

    private function getCountPages()
    {
        return isset($this->countPages) ? $this->countPages . self::PAGES_COUNT : self::INFO_NOT_AVAIBLE_TEXT;
    }

    private function getInternetPrice()
    {
        return isset($this->internetPrice) ? $this->addCurrency($this->internetPrice) : self::INFO_NOT_AVAIBLE_TEXT;
    }

    private function getLocalPrice()
    {
        return isset($this->localPrice) ? $this->addCurrency($this->localPrice) : self::INFO_NOT_AVAIBLE_TEXT;
    }

    private function applyBoldStyle($string)
    {
        return self::BOLD_TEXT_STYLE_SYMBOL . $string . self::BOLD_TEXT_STYLE_SYMBOL;
    }

    private function applyItalicsStyle($string)
    {
        return self::ITALICS_TEXT_STYLE_SYMBOL . $string. self::ITALICS_TEXT_STYLE_SYMBOL;
    }

    private function addCurrency($price)
    {
        return $price . self::CURRENCY;
    }
}