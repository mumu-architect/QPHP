<?php declare(strict_types=1);
namespace qphp\ValidateTest;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: xing
 * Date: 03/08/2017
 * Time: 9:07 PM
 */

final class ValidateTest extends TestCase
{
    public static function additionProvider(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 1],
            [1, 1, 3],
        ];
    }

    #[DataProvider('additionProvider')]
    public function testAdd(int $a, int $b, int $expected): void
    {
        $this->assertSame($expected, $a + $b);
    }
}

