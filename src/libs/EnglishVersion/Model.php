<?php

namespace App\EnglishVersion;

use Nette\Database\Connection;

class Model
{
    /**
     * @var Connection
     */
    private $database;


    public function __construct(Connection $database)
    {
        $this->database = $database;
    }


    public function downloadNextPageForPortal($portal)
    {
        $articleForDownload = $this->getArticleByPortal($portal);
        // download english page..
        // save english page
        // or save information about missing page in english version
    }


    /**
     * Get no-download page for concrete portal $portalName
     * @todo missing test
     * @param string $portalName
     * @return array
     */
    public function getArticleByPortal($portalName)
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
}
