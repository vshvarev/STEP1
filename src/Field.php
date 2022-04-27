<?php

namespace src;

class Field
{
    private mixed $key;
    private mixed $value;

    public function __construct(mixed $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function setKey(mixed $key)
    {
        $this->key = $key;
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function setValue(mixed $value)
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}