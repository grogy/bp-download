<?php

namespace App\EnglishVersion;

use Atrox\Matcher;

class EnglishParser
{
    public function getContentFromTextarea($inputInHtml)
    {
        $textareaMatcher = Matcher::single('//textarea')->fromHtml();
        return $textareaMatcher($inputInHtml);
    }


    public function getTextFromH1($inputInHtml)
    {
        $textareaMatcher = Matcher::single('//h1')->fromHtml();
        return $textareaMatcher($inputInHtml);
    }
}
