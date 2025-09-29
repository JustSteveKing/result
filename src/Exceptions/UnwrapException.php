<?php

declare(strict_types=1);

namespace JustSteveKing\Result\Exceptions;

use RuntimeException;
use Throwable;

final class UnwrapException extends RuntimeException
{
    public static function becauseErr(string $message, Throwable $previous): self
    {
        return new self($message, previous: $previous);
    }
}
