<?php

require_once __DIR__ . '/../vendor/autoload.php';

use src\CSVReader;

$csv = new CSVReader('../test.csv');

foreach ($csv->chunks() as $chunk) {
    var_dump($chunk);
}
