<?php

namespace src;

class Row
{
    public $fields = [];

    public function setField(Field $field)
    {
        $this->fields[] = $field;
    }

}