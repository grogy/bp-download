<?php

namespace App\EnglishVersion;

class PageDownloader
{
    public function downloadPage($urlFromInternet)
    {
        return file_get_contents($urlFromInternet);
    }
}
