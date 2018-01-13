<?php

namespace Com\Dritan\Demographics\Exception;


class InvalidParameterException extends \InvalidArgumentException
{

    public function __construct(array $parameters)
    {
        $message = 'The given parameters';

        $i = 0;
        foreach ($parameters as $parameter) {
            $message .= ' "' . $parameter . '" ';
            if (++$i < count($parameters)) {
                $message .= 'and';
            }
        }
        $message .= ' is/are invalid.';
        parent::__construct($message);
    }

}