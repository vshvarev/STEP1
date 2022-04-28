<?php

namespace src;

use Generator;

final class CSVReader
{
    private const CHUNK_LENGTH = 3;
    private const SEPARATOR = ';';
    private $file;
    private array $headers;
    private int $countOfHeaders;

    public function __construct(string $filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    public function rows(): Generator
    {
        $this->updateHeaders();
        $this->updateCountOfHeaders();

        while (!feof($this->file)) {
            $rowInArray = $this->readSingleRow();
            $row = new Row();

            for ($i = 0; $i < $this->countOfHeaders; $i++) {
                $field = new Field($this->getHeaders($i), $rowInArray[$i]);
                $row->setField($field);
            }

            yield $row;
        }
        $this->closeForRead();
    }

    public function chunks(): Generator
    {
        $this->updateHeaders();
        $this->updateCountOfHeaders();

        while (!feof($this->file)) {
            $counter = 0;
            $chunk = [];

            while ($counter < self::CHUNK_LENGTH && !feof($this->file)) {
                $rowInArray = fgetcsv($this->file, null, self::SEPARATOR);
                $row = new Row();

                for ($i = 0; $i < $this->countOfHeaders; $i++) {
                    $field = new Field($this->getHeaders($i), $rowInArray[$i]);
                    $row->setField($field);
                }

                $chunk[] = $row;
                $counter++;
            }

            yield $chunk;
        }
        $this->closeForRead();
    }

    private function updateHeaders()
    {
        $this->headers = $this->readSingleRow();
    }

    private function getHeaders(int $id)
    {
        return $this->headers[$id];
    }

    private function updateCountOfHeaders()
    {
        $this->countOfHeaders = count($this->headers);
    }

    private function readSingleRow(): array
    {
        return fgetcsv($this->file, null, self::SEPARATOR);
    }

    private function closeForRead()
    {
        fclose($this->file);
    }
}