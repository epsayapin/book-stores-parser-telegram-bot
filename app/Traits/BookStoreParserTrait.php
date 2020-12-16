<?php


namespace App\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait BookStoreParserTrait
{
    public static function createCrawlerByUrl($url): Crawler
    {
        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        $str = $doc->saveHTML();
        libxml_use_internal_errors(false);

        return new Crawler($str);
    }
}