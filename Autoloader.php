<?php

class Autoloader {

    static function register() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class) {
        $parts = explode('\\', $class);
        $classPath = './src';

        $i = 0;
        foreach ($parts as $part) {
            $classPath .= '/';
            $classPath .= $i++ < count($parts) - 1 ? strtolower($part) : $part;
        }

        require $classPath . '.php';
    }

}
