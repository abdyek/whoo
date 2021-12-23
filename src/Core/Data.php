<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;

class Data extends Core
{
    private array $data;

    public function __construct(?array $data)
    {
        $this->data = $data ?? [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
