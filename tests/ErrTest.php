<?php

declare(strict_types=1);

namespace JustSteveKing\Result\Tests;

use JustSteveKing\Result\Err;
use JustSteveKing\Result\Exceptions\UnwrapException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ErrTest extends TestCase
{
    public function test_is_err_and_error(): void
    {
        $e = new RuntimeException('boom');
        $err = new Err($e);

        $this->assertTrue($err->isErr());
        $this->assertFalse($err->isOk());
        $this->assertSame($e, $err->error());
        $this->assertSame(123, $err->valueOr(123));
    }

    public function test_unwrap_and_expect_throw(): void
    {
        $this->expectException(UnwrapException::class);

        (new Err(new RuntimeException('nope')))->unwrap();
    }
}
