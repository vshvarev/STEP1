<?php

namespace src;

use Generator;

class CSVReader
{
    private const CHUNK_LENGTH = 3;
    private $file;
    private array $columns;
    private int $countOfColumns;

    public function __construct(string $filePath)
    {
        $this->file = fopen($filePath, 'r');
    }

    public function closeForRead()
    {
        fclose($this->file);
    }

    public function setColumns()
    {
        $this->columns = $this->readSingleRow();
    }

    public function getColumns($id)
    {
        return $this->columns[$id];
    }

    public function setCountOfColumns()
    {
        $this->countOfColumns = count($this->columns);
    }

    public function readSingleRow(): array
    {
        return fgetcsv($this->file, null, ';');
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
            $count = 0;
            $chunk = [];
            while ($count < self::CHUNK_LENGTH && !feof($this->file)) {
                $rowInArray = fgetcsv($this->file, null, ';');
                $row = new Row();
                for ($i = 0; $i < $this->countOfColumns; $i++) {
                    $field = new Field($this->getColumns($i), $rowInArray[$i]);
                    $row->setField($field);
                }
                $chunk[] = $row;
                $count++;
            }
            yield $chunk;
        }
        $this->closeForRead();
    }
}