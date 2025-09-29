<?php

declare(strict_types=1);

namespace JustSteveKing\Result\Tests;

use JustSteveKing\Result\Ok;
use PHPUnit\Framework\TestCase;

final class OkTest extends TestCase
{
    public function test_is_ok_and_unwrap(): void
    {
        $ok = new Ok(42);

        $this->assertTrue($ok->isOk());
        $this->assertFalse($ok->isErr());
        $this->assertSame(42, $ok->unwrap());
        $this->assertSame(42, $ok->expect('should not throw'));
        $this->assertNull($ok->error());
        $this->assertSame(42, $ok->valueOr(0));
    }

    public function test_map_and_andThen(): void
    {
        $ok = new Ok(2);

        $mapped = $ok->map(fn(int $n): int => $n * 2);
        $this->assertSame(4, $mapped->unwrap());

        $andThen = $ok->andThen(fn(int $n) => new Ok($n + 5));
        $this->assertSame(7, $andThen->unwrap());
    }
}
