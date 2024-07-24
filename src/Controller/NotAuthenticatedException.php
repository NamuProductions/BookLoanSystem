<?php

namespace App\Controller;

use RuntimeException;

class NotAuthenticatedException extends RuntimeException
{
    public function __construct(string $message = "User not authenticated", int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
