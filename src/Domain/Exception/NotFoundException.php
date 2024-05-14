<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = "User not found", int $code = 404)
    {
        parent::__construct($message, $code);
    }
}