<?php

namespace src;

class Row
{
    public array $fields;

    public function setField(Field $field)
    {
        $this->fields[] = $field;
    }
}