<?php

namespace Angle\Utilities\Exception;

use RuntimeException;
use Throwable;

class RuntimeExceptionWithPublicMessage extends RuntimeException
{
    private $publicMessage = '';

    public function __construct(string $message, string $publicMessage)
    {
        parent::__construct($message, 0, null);
        $this->publicMessage = $publicMessage;
    }

    public function getPublicMessage(): string
    {
        return $this->publicMessage;
    }

}