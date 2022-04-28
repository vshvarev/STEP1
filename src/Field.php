<?php

namespace src;

final class Field
{
    private mixed $header;
    private mixed $value;

    public function __construct(mixed $key, mixed $value)
    {
        $this->header = $key;
        $this->value = $value;
    }

    public function getHeader(): mixed
    {
        return $this->header;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}