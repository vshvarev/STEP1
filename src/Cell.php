<?php

namespace src;

final class Cell
{
    private string $header;
    private mixed $value;

    public function __construct(string $header, mixed $value)
    {
        $this->header = $header;
        $this->value = $value;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}