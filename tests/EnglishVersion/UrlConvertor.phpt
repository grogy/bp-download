<?php

use Tester\Assert;
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/libs/EnglishVersion/UrlConvertor.php';
require __DIR__ . '/../../src/libs/EnglishVersion/PageDownloader.php';
Tester\Environment::setup();

$czechPageHTML = [];
$czechPageHTML['php'] = <<<'EOT'
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <ul>
            <li class="interlanguage-link interwiki-en">
                <a href="//en.wikipedia.org/wiki/PHP" title="PHP – angličtina" lang="en" hreflang="en">English</a>
            </li>
        </ul>
    </body>
</html>
EOT;
$czechPageHTML['praha'] = <<<'EOT'
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <ul>
            <li class="interlanguage-link interwiki-az">
                <a href="//en.wikipedia.org/wiki/Prague">Azərbaycanca</a>
            </li>
            <li class="interlanguage-link interwiki-en">
                <a href="//en.wikipedia.org/wiki/Prague">English</a>
            </li>
        </ul>
    </body>
</html>
EOT;
$czechPageHTML['radici-algoritmus'] = <<<'EOT'
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <ul>
            <li class="interlanguage-link interwiki-az">
                <a href="//az.wikipedia.org/wiki/S%C4%B1ralama_alqoritmi" title="Sıralama alqoritmi – ázerbájdžánština" lang="az" hreflang="az">Azərbaycanca</a>
            </li>
            <li class="interlanguage-link interwiki-en">
                <a href="//en.wikipedia.org/wiki/Sorting_algorithm" title="Sorting algorithm – angličtina" lang="en" hreflang="en">English</a>
            </li>
            <li class="interlanguage-link interwiki-fr">
                <a href="//fr.wikipedia.org/wiki/Algorithme_de_tri" title="Algorithme de tri – francouzština" lang="fr" hreflang="fr">Français</a>
            </li>
        </ul>
    </body>
</html>
EOT;
$czechPageHTML['licno'] = <<<'EOT'
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <p>Page without links to foreign pages..</p>
    </body>
</html>
EOT;

$databaseMock = \Mockery::mock('Nette\Database\Connection')->makePartial();
$pageDownloader = \Mockery::mock('App\EnglishVersion\PageDownloader');
$pageDownloader->shouldReceive('downloadPage')->with('https://cs.wikipedia.org/wiki/PHP')->andReturn($czechPageHTML['php']);
$pageDownloader->shouldReceive('downloadPage')->with('https://cs.wikipedia.org/wiki/Praha')->andReturn($czechPageHTML['praha']);
$pageDownloader->shouldReceive('downloadPage')->with('https://cs.wikipedia.org/wiki/%C5%98adic%C3%AD_algoritmus')->andReturn($czechPageHTML['radici-algoritmus']);
$pageDownloader->shouldReceive('downloadPage')->with('https://cs.wikipedia.org/wiki/%C5%98adic%C3%AD_algoritmus')->andReturn($czechPageHTML['radici-algoritmus']);
$pageDownloader->shouldReceive('downloadPage')->with('https://cs.wikipedia.org/wiki/Li%C4%8Dno_%28Ba%C4%8Dalky%29')->andReturn($czechPageHTML['licno']);
$urlConvertor = new App\EnglishVersion\UrlConvertor($pageDownloader);

Assert::same('https://cs.wikipedia.org/wiki/PHP', $urlConvertor->getURLForCzechArticle('PHP'));
Assert::same('https://cs.wikipedia.org/wiki/%C5%98adic%C3%AD_algoritmus', $urlConvertor->getURLForCzechArticle('Řadicí algoritmus'));

Assert::same("https://en.wikipedia.org/wiki/PHP", $urlConvertor->getURLForEnglishArticle('PHP'));
Assert::same("https://en.wikipedia.org/wiki/Prague", $urlConvertor->getURLForEnglishArticle('Praha'));
Assert::same("https://en.wikipedia.org/wiki/Sorting_algorithm", $urlConvertor->getURLForEnglishArticle('Řadicí algoritmus'));
Assert::exception(function() use ($urlConvertor){
    $urlConvertor->getURLForEnglishArticle('Lično (Bačalky)');
}, 'App\EnglishVersion\NotExistEnglishUrl');
