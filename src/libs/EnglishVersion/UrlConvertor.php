<?php

namespace App\EnglishVersion;

use Atrox\Matcher;
use Exception;

class UrlConvertor
{
    private $pageDownloader;


    function __construct(PageDownloader $pageDownloader)
    {
        $this->pageDownloader = $pageDownloader;
    }


    public function getURLForEnglishArticle($articleCzechName)
    {
        $urlFromCzechVersion = $this->getURLForCzechArticle($articleCzechName);
        $HTMLFromCzechPage = $this->pageDownloader->downloadPage($urlFromCzechVersion);
        var_dump($HTMLFromCzechPage);
        $matcherObject = Matcher::single('//li[starts-with(@class, "interlanguage-link interwiki-en")]/a/@href')->fromHtml();
        $englishURL = $matcherObject($HTMLFromCzechPage);
        if (empty($englishURL)) {
            throw new NotExistEnglishUrl;
        }
        $englishURL = 'https:' . $englishURL;
        return $englishURL;
    }


    public function getURLForCzechArticle($articleCzechName)
    {
        $articleCzechName = str_replace(' ', '_', $articleCzechName);
        return 'https://cs.wikipedia.org/wiki/' . urlencode($articleCzechName);
    }
}


class NotExistEnglishUrl extends Exception {}
