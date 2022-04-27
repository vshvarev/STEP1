<?php

namespace src;

class CSVReader
{
    const CHUNK_LENGTH = 3;
    protected $file;
    protected $columns = [];
    protected $countOfColumns;

    public function __construct($filePath)
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

    public function readSingleRow()
    {
        return fgetcsv($this->file, null, ';');
    }

    public function rows()
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

    public function chunks()
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