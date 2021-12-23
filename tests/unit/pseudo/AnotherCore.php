<?php

namespace Pseudo;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Core\AbstractController;

class AnotherCore extends Core
{
    public function getController(): AbstractController
    {
        return $this->controller;
    }
}
