<?php 
use Symfony\Component\DomCrawler\Crawler;

$doc = new \DOMDocument();
$filepath = "SearchPageExample/ChcnnMain.html";
libxml_use_internal_errors(true);
$doc->loadHTMLFile($filepath);
$str = $doc->saveHTML();
$crawler = new Crawler($str);

print $crawler->html();