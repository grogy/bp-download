<?php

namespace App\EnglishVersion;

use Nette\Database\Connection;

class Model
{
    /**
     * @var Connection
     */
    private $database;

    private $urlConvertor;

    private $pageDownloader;

    private $htmlParser;


    public function __construct(Connection $database, UrlConvertor $urlConvertor, PageDownloader $pageDownloader, EnglishParser $parser)
    {
        $this->database = $database;
        $this->urlConvertor = $urlConvertor;
        $this->pageDownloader = $pageDownloader;
        $this->htmlParser = $parser;
    }


    public function downloadNextPageForPortal($portal)
    {
        $articleForDownload = $this->getArticleByPortal($portal);
        $englishPageUrl = $this->urlConvertor->getURLForEnglishArticle($articleForDownload['name']);
        $englishPageUrlForEdit = $this->urlConvertor->getUrlForEditPage($englishPageUrl);
        $englishPage = $this->pageDownloader->downloadPage($englishPageUrlForEdit);
        $wikiContentFromEnglishPage = $this->htmlParser->getContentFromTextarea($englishPage);
        $headingFromEnglishPage = substr($this->htmlParser->getTextFromH1($englishPage), 16);
        $this->saveEnglishPage($headingFromEnglishPage, $wikiContentFromEnglishPage);
        $this->saveAssociation($articleForDownload['id']);
    }


    /**
     * Get no-download page for concrete portal $portalName
     * @todo missing test
     * @todo miss articles with exist english version article
     * @param string $portalName
     * @return array
     */
    private function getArticleByPortal($portalName)
    {
        $query = '
            SELECT a.*
            FROM articles a
            JOIN article_portals m ON a.id = m.article_id
            JOIN portals p ON p.id = m.portal_id
            WHERE p.name = ?
            LIMIT 1';
        $article = $this->database->query($query, $portalName)->fetch();
        return $article;
    }


    /**
     * @param $name string
     * @param $wikiContent string
     * @todo missing test
     * @return \Nette\Database\ResultSet
     */
    private function saveEnglishPage($name, $wikiContent)
    {
        $query = '
            INSERT INTO articles(`language`, `name`, `text`)
            VALUES(?, ?, ?)';
        return $this->database->query($query, 'en', $name, $wikiContent);
    }


    /**
     * @param $idForCzechArticle int
     * @todo missing test
     */
    private function saveAssociation($idForCzechArticle)
    {
        $idForEnglishArticle = $this->database->query('SELECT LAST_INSERT_ID() id')->fetch();
        $query = '
            INSERT INTO articles_language_association(article_czech, article_english)
            VALUES(?, ?)';
        $this->database->query($query, $idForCzechArticle, $idForEnglishArticle['id']);
    }
}
