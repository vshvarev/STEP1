<?php

namespace src;

use Generator;

final class CSVReader
{
    private const SEPARATOR = ';';

    public function getRows(string $filePath): Generator
    {
        $file = $this->openFile($filePath);
        $headers = $this->updateHeaders($file);

        while ($this->endOfFileNotReached($file)) {
            $rowInArray = $this->readSingleRow($file);
            $row = new Row();

            foreach ($rowInArray as $index => $value) {
                $row->addCell(new Cell($headers[$index], $value));
            }

            yield $row;
        }
        $this->closeForRead($file);
    }

    public function getChunks(string $filePath, $chunkSize): Generator
    {
        foreach ($this->getRows($filePath) as $row) {
            $chunk = [];
            while (count($chunk) < $chunkSize) {
                $chunk[] = $row;
            }
            yield $chunk;
        }
    }

    /**
     * @return false|resource
     */
    public static function openFile(string $filePath)
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
     * @param resource $file
     */
    private static function readSingleRow($file): array
    {
        return fgetcsv($file, null, self::SEPARATOR);
    }

    /**
     * @param resource $file
     */
    private static function endOfFileNotReached($file): bool
    {
        return !feof($file);
    }

    /**
     * @param resource $file
     */
    private static function closeForRead($file): void
    {
        fclose($file);
    }
}