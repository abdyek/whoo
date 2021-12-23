<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\AbstractController;

class Core
{
    protected AbstractController $controller;

    public function setController(AbstractController $controller)
    {
        $this->controller = $controller;
    }
}
