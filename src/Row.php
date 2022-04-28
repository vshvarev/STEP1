<?php

namespace src;

final class Row
{
    private array $cells;

    public function addCell(Cell $cell): void
    {
        $this->cells[] = $cell;
    }

    public function getCell(int $id): Cell
    {
        return $this->cells[$id];
    }
}