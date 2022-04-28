<?php

namespace src;

final class Row
{
    private array $fields;

    public function addField(Field $field): void
    {
        $this->fields[] = $field;
    }

    public function getField(int $id): Field
    {
        return $this->fields[$id];
    }
}