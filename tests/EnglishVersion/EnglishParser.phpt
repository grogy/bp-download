<?php

use Tester\Assert;
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/libs/EnglishVersion/EnglishParser.php';
Tester\Environment::setup();

$englishParser = new \App\EnglishVersion\EnglishParser();

$input = <<<'EOT'
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <h1>This is heading for page</h1>
        <textarea>
== This is content in wiki syntax ==
        </textarea>
    </body>
</html>
EOT;
$expected = "== This is content in wiki syntax ==";
Assert::same($expected, $englishParser->getContentFromTextarea($input));
