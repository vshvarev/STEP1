<?php

namespace src;

use Generator;

class CSVReader
{
    private const CHUNK_LENGTH = 3;
    private const SEPARATOR = ';';
    private $file;
    private array $columns;
    private int $countOfColumns;

    public function __construct(string $filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    public function rows(): Generator
    {
        $this->setColumns();
        $this->setCountOfColumns();
        while (!feof($this->file)) {
            $rowInArray = $this->readSingleRow();
            $row = new Row();
            for ($i = 0; $i < $this->countOfColumns; $i++) {
                $field = new Field($this->getColumns($i), $rowInArray[$i]);
                $row->setField($field);
            }
            yield $row;
        }
        $this->closeForRead();
    }

    public function chunks(): Generator
    {
        $this->setColumns();
        $this->setCountOfColumns();
        while (!feof($this->file)) {
            $counter = 0;
            $chunk = [];
            while ($counter < self::CHUNK_LENGTH && !feof($this->file)) {
                $rowInArray = fgetcsv($this->file, null, self::SEPARATOR);
                $row = new Row();
                for ($i = 0; $i < $this->countOfColumns; $i++) {
                    $field = new Field($this->getColumns($i), $rowInArray[$i]);
                    $row->setField($field);
                }
                $chunk[] = $row;
                $counter++;
            }
            yield $chunk;
        }
        $this->closeForRead();
    }

    private function setColumns()
    {
        $this->columns = $this->readSingleRow();
    }

    private function getColumns(int $id)
    {
        return $this->columns[$id];
    }

    private function setCountOfColumns()
    {
        $this->countOfColumns = count($this->columns);
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