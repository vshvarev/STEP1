<?php

namespace src;

final class Cell
{
    public function __construct(
        private string $header,
        private mixed $value,
    ) {}

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}