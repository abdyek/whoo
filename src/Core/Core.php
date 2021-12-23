<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Controller;

class Core
{
    protected Controller $controller;

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }
}
