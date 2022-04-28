<?php

namespace src;

use Generator;

final class CSVReader
{
    private const CHUNK_LENGTH = 3;
    private const SEPARATOR = ';';

    public function getRows(string $filePath): Generator
    {
        $file = $this->openFile($filePath);
        $headers = $this->updateHeaders($file);
        $countOfHeaders = $this->updateCountOfHeaders($headers);

        while (!feof($file)) {
            $rowInArray = $this->readSingleRow($file);
            $row = new Row();

            for ($i = 0; $i < $countOfHeaders; $i++) {
                $field = new Cell($headers[$i], $rowInArray[$i]);
                $row->addCell($field);
            }

            yield $row;
        }
        $this->closeForRead($file);
    }

    public function getChunks(string $filePath): Generator
    {
        $file = $this->openFile($filePath);
        $headers = $this->updateHeaders($file);
        $countOfHeaders = $this->updateCountOfHeaders($headers);

        while (!feof($file)) {
            $counter = 0;
            $chunk = [];

            while ($counter < self::CHUNK_LENGTH && !feof($file)) {
                $rowInArray = $this->readSingleRow($file);
                $row = new Row();

                for ($i = 0; $i < $countOfHeaders; $i++) {
                    $field = new Cell($headers[$i], $rowInArray[$i]);
                    $row->addCell($field);
                }

                $chunk[] = $row;
                $counter++;
            }

            yield $chunk;
        }
        $this->closeForRead($file);
    }

    /**
     * @return false|resource
     */
    public function openFile(string $filePath)
    {
        return fopen($filePath, 'r');
    }

    /**
     * @param resource $file
     */
    private function updateHeaders($file): array
    {
        return $this->readSingleRow($file);
    }

    /**
     * @param array<string> $headers
     */
    private function updateCountOfHeaders(array $headers): int
    {
        return count($headers);
    }

    /**
     * @param resource $file
     */
    private function readSingleRow($file): array
    {
        return fgetcsv($file, null, self::SEPARATOR);
    }

    /**
     * @param resource $file
     */
    private function closeForRead($file): void
    {
        fclose($file);
    }
}