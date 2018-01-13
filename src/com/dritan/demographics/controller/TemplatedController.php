<?php

namespace Com\Dritan\Demographics\Controller;

use Com\Dritan\Demographics\Exception\VariableNotFoundException;


class TemplatedController
{

    private $vars = [];

    protected function bind($key, $value)
    {
        $this->vars[$key] = $value;
    }

    protected function has($key)
    {
        return isset($this->vars[$key]);
    }

    protected function get($key)
    {
        if ($this->has($key)) {
            return $this->vars[$key];
        }
        throw new VariableNotFoundException($key);
    }

    protected function render($tplName)
    {
        $filePath = './templates/' . $tplName . '.html.php';
        if (!is_file($filePath)) {
            $filePath = './templates/404.html';
        }
        require $filePath;
    }

}