<?php

namespace src;

class Field
{
    public $key;
    public $value;

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}