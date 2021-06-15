<?php

declare(strict_types=1);

namespace Phlex\Ui\Tests;

use Phlex\Ui\Exception;
use Phlex\Ui\Locale;

class LocaleTest extends \Phlex\Core\PHPUnit\TestCase
{
    public function testException(): void
    {
        $this->expectException(Exception::class);
        $exc = new Locale();
    }

    public function testGetPath(): void
    {
        $rootDir = realpath(dirname(__DIR__) . '/src/..');
        $this->assertSame($rootDir . \DIRECTORY_SEPARATOR . 'locale', realpath(dirname(Locale::getPath())) . \DIRECTORY_SEPARATOR . basename(Locale::getPath()));
    }
}
