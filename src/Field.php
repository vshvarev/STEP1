<?php

namespace src;

final class Field
{
    private mixed $key;
    private mixed $value;

    public function __construct(mixed $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}