<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Exception\InvalidDataException;

class Data extends Core
{
    private array $content;

    public function __construct(?array $content = [])
    {
        $this->content = $content;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }

}
