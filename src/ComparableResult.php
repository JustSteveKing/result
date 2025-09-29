<?php

declare(strict_types=1);

namespace JustSteveKing\Result;

use JustSteveKing\Result\Contracts\ResultInterface;
use Throwable;

/**
 * Comparable Result for values that are array-keys (int|string).
 */
final class ComparableResult
{
    /**
     * @var ResultInterface<int|string>
     */
    private readonly ResultInterface $inner;

    /**
     * @param ResultInterface<int|string> $inner
     */
    private function __construct(ResultInterface $inner)
    {
        $this->inner = $inner;
    }

    /**
     * @param int|string $value
     * @return self
     */
    public static function ok(int|string $value): self
    {
        return new self(new Ok($value));
    }

    /**
     * @param Throwable $error
     * @return self
     */
    public static function err(Throwable $error): self
    {
        // When wrapping an Err, we still type the inner as ResultInterface<int|string>
        // because consumers should not unwrap Err; equals() guards on isOk().
        return new self(new Err($error));
    }

    public function equals(int|string $other): bool
    {
        return $this->inner->isOk() && $this->inner->unwrap() === $other;
    }

    /**
     * @return ResultInterface<int|string>
     */
    public function inner(): ResultInterface
    {
        return $this->inner;
    }
}
