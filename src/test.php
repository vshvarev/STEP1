<?php

require_once __DIR__ . '/../vendor/autoload.php';

use src\CSVReader;

$csv = new CSVReader();

foreach ($csv->getChunks('../test.csv') as $chunk) {
    var_dump($chunk);
}

foreach ($csv->getRows('../test.csv') as $chunk) {
    var_dump($chunk);
}
