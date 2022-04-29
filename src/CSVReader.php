<?php

namespace src;

use Generator;

final class CSVReader
{
    private const SEPARATOR = ';';
    private const DEFAULT_CHUNK_SIZE = 50;

    public function getRows(string $filePath): Generator
    {
        $file = self::openFile($filePath);
        $headers = self::updateHeaders($file);

        while (self::endOfFileNotReached($file)) {
            $rowInArray = self::readSingleRow($file);
            $row = new Row();

            foreach ($rowInArray as $index => $value) {
                $row->addCell(new Cell($headers[$index], $value));
            }

            yield $row;
        }
        self::closeForRead($file);
    }

    public function getChunks(string $filePath, int $chunkSize = self::DEFAULT_CHUNK_SIZE): Generator
    {
        $chunkSize = self::validateChunkSize($chunkSize);
        $chunk = [];

        foreach (self::getRows($filePath) as $row) {
            $chunk[] = $row;

            if (count($chunk) >= $chunkSize) {
                yield $chunk;
                $chunk = [];
            }
        }

        yield $chunk;
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
        return self::readSingleRow($file);
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

    private static function validateChunkSize(int $chunkSize): int
    {
        if ($chunkSize < 1 || $chunkSize > 10000) {
            $chunkSize = self::DEFAULT_CHUNK_SIZE;
        }

        return $chunkSize;
    }
}