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
            $values = self::readSingleRow($file);

            yield self::createRow($headers, $values);
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

    private function createRow(array $headers, array $values): Row
    {
        $row = new Row();
        for ($i = 0; $i < count($headers); $i++) {
            $row->addCell(new Cell($headers[$i], $values[$i]));
        }
        return $row;
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