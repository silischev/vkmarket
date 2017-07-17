<?php

namespace Asil\VkMarket\Exception;

use Exception;

class VkException extends Exception
{
    public function __construct($message, $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }

}