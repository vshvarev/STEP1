<?php

require_once __DIR__ . '/../vendor/autoload.php';

use src\CSVReader;

$csv = new CSVReader();

foreach ($csv->getChunks('../test.csv', -1) as $chunk) {
    var_dump($chunk);
}

