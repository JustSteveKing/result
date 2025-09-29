<?php

declare(strict_types=1);

namespace JustSteveKing\Result;

use JustSteveKing\Result\Contracts\ResultInterface;
use Throwable;

/**
 * Static factory for Ok / Err results.
 *
 * @template T
 */
final class Result
{
    private function __construct() {}

    /**
     * @param T $value
     * @return ResultInterface<T>
     */
    public static function ok(mixed $value): ResultInterface
    {
        return new Ok($value);
    }

    /**
     * @param Throwable $error
     * @return ResultInterface<mixed>
     */
    public static function err(Throwable $error): ResultInterface
    {
        return new Err($error);
    }
}
