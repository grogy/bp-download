<?php

namespace App\ListForDownload;

use Atrox\Matcher;
use DateTime;
use DateTimeZone;
use Nette\Database\Connection;

class ListOfLinks
{
    /**
     * @var Connection
     */
    private $database;

    private $baseUrl;

    const STORAGE_INDEX = 'backup-index.html';


    public function __construct(Connection $database, $baseUrl)
    {
        $this->database = $database;
        $this->baseUrl = $baseUrl;
    }


    /**
     * Generate file with `wget` commands
     * @param string $pathForGenerate path to save
     */
    public function generateDownloadFile($pathForGenerate)
    {
        $string = '';
        $i = 0;
        foreach ($this->getFiles('cswiki')['All pages, current versions only.'] as $item) {
            $string .= 'wget ' . $this->baseUrl . $item->url . " -O cswiki-page-$i.bz2;\n";
            $string .= "bunzip2 cswiki-page-$i.bz2;\n\n";
        }
        file_put_contents($pathForGenerate, $string);
    }


    public function getLanguagePages()
    {
        $html = file_get_contents($this->baseUrl . self::STORAGE_INDEX);
        $m = Matcher::multi('//li[not(@class)]', [
            'text' => '.',
            'title' => 'a',
            'url' => 'a/@href',
            'done' => 'span',
        ])->fromHtml();
        $parseData = $m($html);
        array_walk($parseData, function(&$value, $key){
            $date = substr($value['text'], 0, 10);
            $value['time'] = new DateTime($date, new DateTimeZone('Europe/London'));
            if (isset($value['done'])) {
                $value['done'] = ($value['done'] == 'Dump complete') ? true : false;
            } else {
                $value['done'] = false;
            }
        });
        $data = [];
        foreach ($parseData as $item) {
            if (empty($item['title'])) {
                continue;
            }
            $data[$item['title']] = [
                'url' => $item['url'],
                'time' => $item['time'],
                'done' => $item['done'],
            ];
        }
        return $data;
    }


    /**
     * @param $language string Language, for example 'enwiki'
     * @return array
     */
    public function getFiles($language)
    {
        $allTypes = $this->getLanguagePages();
        $html = file_get_contents($this->baseUrl . $allTypes[$language]['url']);
        $m = Matcher::multi('//li[@class="done"]', [
            'files' => (object) [
                'name' => 'ul/li/a',
                'url' => 'ul/li/a/@href',
            ],
            'title' => 'span[@class="title"]',
        ])->fromHtml();
        $parseData = $m($html);
        $data = [];
        foreach ($parseData as $item) {
            $data[$item['title']] = [
                $item['files']
            ];
        }
        return $data;
    }
}
