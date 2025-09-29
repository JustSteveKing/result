<?php

declare(strict_types=1);

namespace JustSteveKing\Result\Tests;

use JustSteveKing\Result\ComparableResult;

use function JustSteveKing\Result\{ok, result_match};

use JustSteveKing\Result\Result;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ResultAndHelpersTest extends TestCase
{
    public function test_result_factory_and_helpers(): void
    {
        $r1 = Result::ok('hello');
        $this->assertTrue($r1->isOk());
        $this->assertSame('hello', $r1->unwrap());

        $e = new RuntimeException('fail');
        $r2 = Result::err($e);
        $this->assertTrue($r2->isErr());
        $this->assertSame($e, $r2->error());

        $r3 = ok(10);
        $this->assertSame(20, $r3->map(fn(int $n) => $n * 2)->unwrap());

        $out = result_match(
            ok('x'),
            fn(string $v) => $v . 'y',
            fn() => 'no',
        );
        $this->assertSame('xy', $out);

        $cmp = ComparableResult::ok('foo');
        $this->assertTrue($cmp->equals('foo'));
        $this->assertFalse($cmp->equals('bar'));
    }
}
