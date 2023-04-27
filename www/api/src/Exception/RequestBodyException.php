<?php

declare(strict_types=1);

namespace App\Exception;

class RequestBodyException extends \RuntimeException
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct('Error unmarshalling incomingMessage body', 0, $previous);
    }
}
