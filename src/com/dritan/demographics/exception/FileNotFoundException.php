<?php

namespace Com\Dritan\Demographics\Exception;


class FileNotFoundException extends \Exception
{

    public function __construct($filename) {
        parent::__construct('The file ' . $filename . ' has not be found.');
    }

}