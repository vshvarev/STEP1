<?php

namespace src;

class Field
{
    private mixed $key;
    private mixed $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}