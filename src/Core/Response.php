<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;

class Response extends Core
{
    private array $content;

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): void
    {
        $this->content = $content;
    }
}

