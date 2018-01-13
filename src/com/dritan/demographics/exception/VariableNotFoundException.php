<?php

namespace Com\Dritan\Demographics\Exception;


class VariableNotFoundException extends \Exception
{

    public function __construct($variableName) {
        parent::__construct('The variable ' . $variableName . ' does not exist.');
    }

}