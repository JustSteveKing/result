<?php

declare(strict_types=1);

namespace JustSteveKing\Result\Contracts;

use JustSteveKing\Result\Exceptions\UnwrapException;
use Throwable;

/**
 * @template T
 */
interface ResultInterface
{
    public function isOk(): bool;

    public function isErr(): bool;

    /**
     * @return T
     * @throws UnwrapException
     */
    public function unwrap(): mixed;

    /**
     * @return T
     * @throws UnwrapException
     */
    public function expect(string $message): mixed;

    public function error(): ?Throwable;

    /**
     * @param T $default
     * @return T
     */
    public function valueOr(mixed $default): mixed;

    /**
     * @template S
     * @param callable(T): S $fn
     * @return ResultInterface<S>
     */
    public function map(callable $fn): ResultInterface;

    /**
     * @param callable(Throwable): Throwable $fn
     * @return ResultInterface<T>
     */
    public function mapErr(callable $fn): ResultInterface;

    /**
     * @template S
     * @param callable(T): ResultInterface<S> $fn
     * @return ResultInterface<S>
     */
    public function andThen(callable $fn): ResultInterface;

    /**
     * @param callable(Throwable): ResultInterface<T> $fn
     * @return ResultInterface<T>
     */
    public function orElse(callable $fn): ResultInterface;

    /**
     * @param callable(T): void $fn
     * @return $this
     */
    public function tap(callable $fn): self;

    /**
     * @param callable(Throwable): void $fn
     * @return $this
     */
    public function tapErr(callable $fn): self;
}
