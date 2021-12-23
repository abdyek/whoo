<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;

class Config extends Core
{
    private array $requiredMap;

    public function getRequiredMap(): array
    {
        return $this->requiredMap;
    }

    public function setRequiredMap(array $requiredMap): void
    {
        $this->requiredMap = $requiredMap;
    }

}
