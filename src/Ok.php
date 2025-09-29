<?php

declare(strict_types=1);

namespace JustSteveKing\Result;

use JustSteveKing\Result\Contracts\ResultInterface;
use Throwable;

/**
 * @template T
 * @implements ResultInterface<T>
 */
final class Ok implements ResultInterface
{
    /**
     * @param T $value
     */
    public function __construct(
        private readonly mixed $value,
    ) {}

    public function isOk(): bool
    {
        return true;
    }

    public function isErr(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function unwrap(): mixed
    {
        return $this->value;
    }

    /**
     * @return T
     */
    public function expect(string $message): mixed
    {
        return $this->value;
    }

    public function error(): ?Throwable
    {
        return null;
    }

    /**
     * @param T $default
     * @return T
     */
    public function valueOr(mixed $default): mixed
    {
        return $this->value;
    }

    /**
     * @template S
     * @param callable(T): S $fn
     * @return Ok<S>
     */
    public function map(callable $fn): ResultInterface
    {
        return new self($fn($this->value));
    }

    /**
     * @param callable(Throwable): Throwable $fn
     * @return ResultInterface<T>
     */
    public function mapErr(callable $fn): ResultInterface
    {
        return $this;
    }

    /**
     * @template S
     * @param callable(T): ResultInterface<S> $fn
     * @return ResultInterface<S>
     */
    public function andThen(callable $fn): ResultInterface
    {
        return $fn($this->value);
    }

    /**
     * @param callable(Throwable): ResultInterface<T> $fn
     * @return ResultInterface<T>
     */
    public function orElse(callable $fn): ResultInterface
    {
        return $this;
    }

    /**
     * @param callable(T): void $fn
     * @return $this
     */
    public function tap(callable $fn): self
    {
        $fn($this->value);

        return $this;
    }

    /**
     * @param callable(Throwable): void $fn
     * @return $this
     */
    public function tapErr(callable $fn): self
    {
        return $this;
    }
}

